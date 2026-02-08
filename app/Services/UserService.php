<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Entity\Student;
use App\Entity\Hostel;
use App\Entity\AcademicYear;
use App\Entity\InstitutionProgram;
use App\Entity\OutpassRequest;
use App\Entity\Logbook;
use App\Entity\WardenAssignment;
use App\Services\OutpassService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService
    )
    {
    }

    public function getUserById(int $id)
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function getUserByEmail(string $email): User|null
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }
    
    public function createUser(array $data): User|bool
    {
        // check if user with email exists
        $user = $this->getUserByEmail($data['email']);
        if ($user !== null) {
            return $user;
        }
        
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['contact'], PASSWORD_DEFAULT, ['cost' => 10]));
        $user->setContactNo($data['contact']);
        $user->setGender($data['gender']);
        $user->setRole($data['role'] ?? UserRole::USER);
        $user->setStatus(UserStatus::INACTIVE);
        $user->setCreatedAt(new DateTime());
        
        $this->em->persist($user);
        $this->em->flush();
        
        return $user;
    }

    public function getWardensByGender(User $user): array
    {
        return $this->em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.gender = :gender')
            ->setParameter('role', UserRole::ADMIN)
            ->setParameter('gender', $user->getGender())
            ->getQuery()
            ->getResult();
    }

    public function removeWarden(int $wardenId): bool
    {
        $user = $this->em->getRepository(User::class)->find($wardenId);

        if ($user) {
            $assignments = $this->em->getRepository(WardenAssignment::class)->findBy([
                'assignedTo' => $user
            ]);
            foreach ($assignments as $assignment) {
                $this->em->remove($assignment);
            }

            $assignedBy = $this->em->getRepository(WardenAssignment::class)->findBy([
                'assignedBy' => $user
            ]);
            foreach ($assignedBy as $assignment) {
                $this->em->remove($assignment);
            }

            $this->em->remove($user);
            $this->em->flush();
            return true;
        }

        return false;
    }

    public function updateWarden(int $wardenId, array $data): User|bool
    {
        $warden = $this->em->getRepository(User::class)->find($wardenId);
        if (!$warden) {
            return false;
        }

        $email = strtolower(trim((string)($data['email'] ?? '')));
        if ($email === '') {
            return false;
        }

        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existing && $existing->getId() !== $warden->getId()) {
            return false;
        }

        $warden->setName($data['name']);
        $warden->setEmail($email);
        $warden->setContactNo($data['contact']);

        $this->em->flush();

        return $warden;
    }

    public function createStudent(array $data, User $user): Student|bool
    {
        $hostel = $data['hostel'];
        $program = $data['program'];
        $academicYear = $data['academic_year'] ?? null;
        $givenYear = (int) $data['year'];

        if (!$data['program'] instanceof InstitutionProgram) {
            return false;
        }

        if ($academicYear !== null && !$academicYear instanceof AcademicYear) {
            return false;
        }
        
        // Check the given year is valid
        if ($givenYear < 1 || $givenYear > $program->getDuration()) {
            return false;
        }


        $student = new Student();
        $student->setUser($user);
        $student->setHostel($hostel);
        $student->setDigitalId($data['digital_id']);
        $student->setYear($givenYear);
        $student->setAcademicYear($academicYear);
        $student->setProgram($program);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo($data['parent_no']);
        $user->setStatus(UserStatus::ACTIVE);
        $student->setUpdatedAt(new DateTime());
        
        $this->em->persist($student);
        $this->em->flush();
        
        return $student;
    }

    public function updateStudent(int $studentId, array $data): Student|bool
    {
        $student = $this->em->getRepository(Student::class)->find($studentId);
        if (!$student) {
            return false;
        }

        $email = strtolower(trim((string)($data['email'] ?? '')));
        if ($email === '') {
            return false;
        }

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser && $existingUser->getId() !== $student->getUser()->getId()) {
            return false;
        }

        $digitalId = (int) ($data['digital_id'] ?? 0);
        if ($digitalId <= 0) {
            return false;
        }

        $existingStudent = $this->em->getRepository(Student::class)->findOneBy(['digitalId' => $digitalId]);
        if ($existingStudent && $existingStudent->getId() !== $student->getId()) {
            return false;
        }

        $hostel = $data['hostel'] ?? null;
        $program = $data['program'] ?? null;
        $academicYear = $data['academic_year'] ?? null;
        $givenYear = (int) ($data['year'] ?? 0);

        if (!$hostel instanceof Hostel) {
            return false;
        }

        if (!$program instanceof InstitutionProgram) {
            return false;
        }

        if ($academicYear !== null && !$academicYear instanceof AcademicYear) {
            return false;
        }

        if ($givenYear < 1 || $givenYear > $program->getDuration()) {
            return false;
        }

        $user = $student->getUser();
        $user->setName($data['name']);
        $user->setEmail($email);
        $user->setContactNo($data['contact']);

        $student->setHostel($hostel);
        $student->setProgram($program);
        $student->setAcademicYear($academicYear);
        $student->setDigitalId($digitalId);
        $student->setYear($givenYear);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo($data['parent_no']);
        $statusFlag = isset($data['status']) ? (bool) $data['status'] : null;
        if ($statusFlag !== null) {
            $user->setStatus($statusFlag ? UserStatus::ACTIVE : UserStatus::INACTIVE);
        }
        $student->setUpdatedAt(new DateTime());

        $this->em->flush();

        return $student;
    }

    /**
     * Get students of a admin based on gender
     */
    public function getStudentsByGender(User $user)
    {
        return $this->em->getRepository(Student::class)->createQueryBuilder('s')
            ->innerJoin('s.user', 'u')
            ->where('u.gender = :gender')
            ->setParameter('gender', $user->getGender())
            ->getQuery()
            ->getResult();
    }

    public function getStudentsByGenderPaginated(User $user, int $page = 1, int $limit = 10, ?string $search = null): array
    {
        $offset = ($page - 1) * $limit;

        $queryBuilder = $this->em->getRepository(Student::class)->createQueryBuilder('s')
            ->innerJoin('s.user', 'u')
            ->where('u.gender = :gender')
            ->setParameter('gender', $user->getGender())
            ->orderBy('u.name', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (!empty($search)) {
            $isNumeric = ctype_digit($search);
            if ($isNumeric) {
                $queryBuilder
                    ->andWhere(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->like('u.name', ':search'),
                            $queryBuilder->expr()->eq('s.digitalId', ':digitalId')
                        )
                    )
                    ->setParameter('search', '%' . $search . '%')
                    ->setParameter('digitalId', (int) $search);
            } else {
                $queryBuilder
                    ->andWhere(
                        $queryBuilder->expr()->like('u.name', ':search')
                    )
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        $paginator = new Paginator($queryBuilder->getQuery(), true);

        return [
            'data' => iterator_to_array($paginator),
            'total' => count($paginator),
            'currentPage' => $page,
            'totalPages' => (int) ceil(count($paginator) / $limit),
        ];
    }
    
    /**
     * Get student by user
     */
    public function getStudentByUser(User $user): Student
    {
        return $this->em->getRepository(Student::class)->findOneBy(['user' => $user]);
    }

    public function getStudentById(int $studentId): ?Student
    {
        return $this->em->getRepository(Student::class)->find($studentId);
    }
    
    /**
     * Remove student
     */
    public function removeStudent(int $studentId): bool
    {
        $student = $this->em->getRepository(Student::class)->find($studentId);

        if ($student) {
            $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy([
                'student' => $student
            ]);

            if (!empty($outpasses)) {
                $this->em->createQueryBuilder()
                    ->delete(Logbook::class, 'l')
                    ->where('l.outpass IN (:outpasses)')
                    ->setParameter('outpasses', $outpasses)
                    ->getQuery()
                    ->execute();

                foreach ($outpasses as $outpass) {
                    $this->outpassService->removeAttachments($outpass);
                    $this->outpassService->removeOutpassDocument($outpass);
                    $this->outpassService->removeQrCode($outpass);
                    $this->em->remove($outpass);
                }
            }

            $user = $student->getUser();
            $this->em->remove($student);
            $this->em->remove($user);
            $this->em->flush();
            return true;
        }

        return false;
    }

    /**
     * Create students from CSV row
     */
    public function importStudentFromCsvRow(array $studentData, User $user)
    {
        $this->createStudent($studentData, $user);
    }
}
