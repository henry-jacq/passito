<?php

namespace App\Controller;

use App\Core\View;
use App\Controller\BaseController;
use App\Entity\User;
use App\Entity\Settings;
use App\Enum\Gender;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Enum\HostelType;
use App\Enum\InstitutionType;
use App\Services\AdminService;
use App\Services\UserService;
use App\Services\AcademicService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SetupController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        private readonly EntityManagerInterface $em,
        private readonly UserService $userService,
        private readonly AcademicService $academicService,
        private readonly AdminService $adminService
    )
    {
    }
    
    public function install(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Setup Page'
        ];
        return parent::render($request, $response, 'setup/install', $args);
    }

    public function update(Request $request, Response $response): Response
    {
        if (strtoupper($request->getMethod()) !== 'POST') {
            return $this->json($response, [
                'status' => false,
                'message' => 'Method not allowed'
            ], 405);
        }

        $data = $request->getParsedBody();
        if (!is_array($data)) {
            return $this->json($response, [
                'status' => false,
                'message' => 'Invalid setup payload'
            ], 422);
        }

        $existingSetup = $this->em->getRepository(Settings::class)->findOneBy(['keyName' => 'setup_complete']);
        if ($existingSetup && $existingSetup->getValue() === 'true') {
            return $this->json($response, [
                'status' => false,
                'message' => 'Setup already completed'
            ], 409);
        }

        $validation = $this->validateSetupData($data);
        if ($validation['status'] === false) {
            return $this->json($response, $validation, 422);
        }

        $connection = $this->em->getConnection();
        $connection->beginTransaction();

        try {
            $institution = null;
            if (!empty($data['institution'])) {
                $institution = $this->createInstitution($data['institution']);
                if (!$institution) {
                    throw new \RuntimeException('Failed to create institution.');
                }
            }

            $superAdmins = $this->createSuperAdmins($data['super_admins']);
            if (count($superAdmins) === 0) {
                throw new \RuntimeException('Failed to create chief wardens.');
            }

            $wardens = [];
            if (!empty($data['wardens'])) {
                $wardens = $this->createWardens($data['wardens']);
            }
            $wardensByEmail = [];
            foreach ($wardens as $warden) {
                $wardensByEmail[strtolower($warden->getEmail())] = $warden;
            }

            $primaryAdmin = $superAdmins[0];
            if (!empty($data['hostels'])) {
                $this->createHostelsWithAssignments($data['hostels'], $primaryAdmin, $wardensByEmail);
            }

            $this->markSetupComplete();

            $connection->commit();

            return $this->json($response, [
                'status' => true,
                'message' => 'Setup completed successfully'
            ]);
        } catch (\Throwable $e) {
            $connection->rollBack();
            return $this->json($response, [
                'status' => false,
                'message' => 'Setup failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function json(Response $response, array $payload, int $status = 200): Response
    {
        $response->getBody()->write(packJson($payload));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }


    private function validateSetupData(array $data): array
    {
        $errors = [];

        $superAdmins = $data['super_admins'] ?? null;
        if (!is_array($superAdmins) || count($superAdmins) === 0) {
            $errors[] = 'At least one chief warden is required.';
        }

        $institution = $data['institution'] ?? null;
        if ($institution !== null) {
            if (!is_array($institution)) {
                $errors[] = 'Institution details are invalid.';
            } else {
                $name = trim((string)($institution['name'] ?? ''));
                $address = trim((string)($institution['address'] ?? ''));
                $type = (string)($institution['type'] ?? '');
                if ($name === '' || $address === '') {
                    $errors[] = 'Institution name and address are required.';
                }
                if (!InstitutionType::isValidInstitution($type)) {
                    $errors[] = 'Institution type is invalid.';
                }
            }
        }

        $wardens = $data['wardens'] ?? [];
        if ($wardens !== null && !is_array($wardens)) {
            $errors[] = 'Warden details are invalid.';
        }

        $hostels = $data['hostels'] ?? [];
        if ($hostels !== null && !is_array($hostels)) {
            $errors[] = 'Hostel details are invalid.';
        }

        foreach (($superAdmins ?? []) as $index => $admin) {
            $label = 'Chief warden ' . ($index + 1);
            $name = trim((string)($admin['name'] ?? ''));
            $email = strtolower(trim((string)($admin['email'] ?? '')));
            $phone = trim((string)($admin['phone'] ?? ''));
            $password = (string)($admin['password'] ?? '');
            $gender = (string)($admin['gender'] ?? '');

            if ($name === '' || $email === '' || $phone === '' || $password === '') {
                $errors[] = $label . ' details are incomplete.';
            }
            if ($email !== '' && !filterEmail($email)) {
                $errors[] = $label . ' email is invalid.';
            }
            if ($password !== '' && strlen($password) < 8) {
                $errors[] = $label . ' password must be at least 8 characters.';
            }
            if ($gender !== '' && !Gender::isValid($gender)) {
                $errors[] = $label . ' gender is invalid.';
            }
        }

        foreach (($wardens ?? []) as $index => $warden) {
            $label = 'Warden ' . ($index + 1);
            $name = trim((string)($warden['name'] ?? ''));
            $email = strtolower(trim((string)($warden['email'] ?? '')));
            $phone = trim((string)($warden['phone'] ?? ''));
            $gender = (string)($warden['gender'] ?? '');

            if ($name === '' || $email === '' || $phone === '') {
                $errors[] = $label . ' details are incomplete.';
            }
            if ($email !== '' && !filterEmail($email)) {
                $errors[] = $label . ' email is invalid.';
            }
            if ($gender !== '' && !Gender::isValid($gender)) {
                $errors[] = $label . ' gender is invalid.';
            }
        }

        foreach (($hostels ?? []) as $index => $hostel) {
            $label = 'Hostel ' . ($index + 1);
            $name = trim((string)($hostel['name'] ?? ''));
            $category = trim((string)($hostel['category'] ?? ''));
            $type = (string)($hostel['type'] ?? '');
            $assignedWarden = trim((string)($hostel['assigned_warden'] ?? ''));

            if ($name === '' || $category === '') {
                $errors[] = $label . ' details are incomplete.';
            }
            if ($type !== '' && !HostelType::isValidHostelType($type)) {
                $errors[] = $label . ' type is invalid.';
            }
            if ($assignedWarden === '') {
                $errors[] = $label . ' must have an assigned warden.';
            }
        }

        return [
            'status' => count($errors) === 0,
            'message' => count($errors) === 0 ? 'valid' : 'Setup validation failed.',
            'errors' => $errors
        ];
    }

    private function createInstitution(array $data): object|bool
    {
        $name = trim((string)($data['name'] ?? ''));
        $existing = $this->em->getRepository(\App\Entity\Institution::class)
            ->findOneBy(['name' => $name]);
        if ($existing) {
            return $existing;
        }

        return $this->academicService->createInstitution([
            'name' => $name,
            'address' => trim((string)($data['address'] ?? '')),
            'type' => (string)($data['type'] ?? '')
        ]);
    }

    private function createSuperAdmins(array $admins): array
    {
        $created = [];
        foreach ($admins as $admin) {
            $email = strtolower(trim((string)($admin['email'] ?? '')));
            $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existing) {
                throw new \RuntimeException('Chief warden email already exists: ' . $email);
            }

            $user = new User();
            $user->setName(trim((string)($admin['name'] ?? '')));
            $user->setEmail($email);
            $user->setPassword(password_hash((string)($admin['password'] ?? ''), PASSWORD_BCRYPT, ['cost' => 12]));
            $user->setGender(Gender::from((string)($admin['gender'] ?? '')));
            $user->setRole(UserRole::SUPER_ADMIN);
            $user->setContactNo(trim((string)($admin['phone'] ?? '')));
            $user->setStatus(UserStatus::ACTIVE);
            $user->setCreatedAt(new \DateTime());

            $this->em->persist($user);
            $created[] = $user;
        }

        $this->em->flush();
        return $created;
    }

    private function createWardens(array $wardens): array
    {
        $created = [];
        foreach ($wardens as $warden) {
            $email = strtolower(trim((string)($warden['email'] ?? '')));
            $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existing) {
                throw new \RuntimeException('Warden email already exists: ' . $email);
            }

            $user = $this->userService->createUser([
                'name' => trim((string)($warden['name'] ?? '')),
                'email' => $email,
                'contact' => trim((string)($warden['phone'] ?? '')),
                'gender' => Gender::from((string)($warden['gender'] ?? '')),
                'role' => UserRole::ADMIN,
            ]);

            if (!$user instanceof User) {
                throw new \RuntimeException('Failed to create warden: ' . $email);
            }

            $user->setStatus(UserStatus::ACTIVE);
            $this->em->flush();

            $created[] = $user;
        }

        return $created;
    }

    private function createHostelsWithAssignments(array $hostels, User $assignedBy, array $wardensByEmail): void
    {
        foreach ($hostels as $hostelData) {
            $hostel = $this->academicService->createHostel([
                'hostel_name' => trim((string)($hostelData['name'] ?? '')),
                'category' => trim((string)($hostelData['category'] ?? '')),
                'hostel_type' => HostelType::from((string)($hostelData['type'] ?? ''))
            ]);

            if ($hostel === false) {
                throw new \RuntimeException('Hostel already exists: ' . $hostelData['name']);
            }

            $assignedEmail = strtolower(trim((string)($hostelData['assigned_warden'] ?? '')));
            if ($assignedEmail !== '' && isset($wardensByEmail[$assignedEmail])) {
                $this->adminService->assignWarden(
                    $wardensByEmail[$assignedEmail],
                    $assignedBy,
                    [$hostel->getId()]
                );
            }
        }
    }

    private function markSetupComplete(): void
    {
        $settingsRepo = $this->em->getRepository(Settings::class);
        $setupSetting = $settingsRepo->findOneBy(['keyName' => 'setup_complete']);
        if (!$setupSetting) {
            $setupSetting = new Settings();
            $setupSetting->setKeyName('setup_complete');
        }
        $setupSetting->setValue('true');
        $setupSetting->setUpdatedAt(new \DateTime());
        $this->em->persist($setupSetting);

        $adminCreated = $settingsRepo->findOneBy(['keyName' => 'admin_created']);
        if (!$adminCreated) {
            $adminCreated = new Settings();
            $adminCreated->setKeyName('admin_created');
        }
        $adminCreated->setValue('true');
        $adminCreated->setUpdatedAt(new \DateTime());
        $this->em->persist($adminCreated);

        $this->em->flush();
    }
}
