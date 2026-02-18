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
        $user->setContactNo((string) $data['contact']);
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
        $warden->setContactNo((string) $data['contact']);

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
        $student->setRollNo((int) $data['roll_no']);
        $student->setYear($givenYear);
        $student->setAcademicYear($academicYear);
        $student->setProgram($program);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo((string) $data['parent_no']);
        $user->setStatus(UserStatus::ACTIVE);
        $student->setUpdatedAt(new DateTime());
        
        $this->em->persist($student);
        $this->em->flush();
        
        return $student;
    }

    /**
     * Create student from DTO
     */
    public function createStudentFromDto(\App\Dto\CreateStudentDto $dto, User $user): Student
    {
        $student = new Student();
        $student->setUser($user);
        $student->setHostel($dto->getHostel());
        $student->setRollNo($dto->getRollNo());
        $student->setYear($dto->getYear());
        $student->setAcademicYear($dto->getAcademicYear());
        $student->setProgram($dto->getProgram());
        $student->setRoomNo($dto->getRoomNo());
        $student->setParentNo($dto->getParentNo());
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

        $rollNo = (int) ($data['roll_no'] ?? 0);
        if ($rollNo <= 0) {
            return false;
        }

        $existingStudent = $this->em->getRepository(Student::class)->findOneBy(['rollNo' => $rollNo]);
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
        $user->setContactNo((string) $data['contact']);

        $student->setHostel($hostel);
        $student->setProgram($program);
        $student->setAcademicYear($academicYear);
        $student->setRollNo($rollNo);
        $student->setYear($givenYear);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo((string) $data['parent_no']);
        $statusFlag = isset($data['status']) ? (bool) $data['status'] : null;
        if ($statusFlag !== null) {
            $user->setStatus($statusFlag ? UserStatus::ACTIVE : UserStatus::INACTIVE);
        }
        $student->setUpdatedAt(new DateTime());

        $this->em->flush();

        return $student;
    }

    /**
     * Update student from DTO
     */
    public function updateStudentFromDto(int $studentId, \App\Dto\UpdateStudentDto $dto): Student|bool
    {
        $student = $this->em->getRepository(Student::class)->find($studentId);
        if (!$student) {
            return false;
        }

        // Check email uniqueness
        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $dto->getEmail()]);
        if ($existingUser && $existingUser->getId() !== $student->getUser()->getId()) {
            return false;
        }

        // Check roll no uniqueness
        $existingStudent = $this->em->getRepository(Student::class)->findOneBy(['rollNo' => $dto->getRollNo()]);
        if ($existingStudent && $existingStudent->getId() !== $student->getId()) {
            return false;
        }

        $user = $student->getUser();
        $user->setName($dto->getName());
        $user->setEmail($dto->getEmail());
        $user->setContactNo($dto->getContact());

        $student->setHostel($dto->getHostel());
        $student->setProgram($dto->getProgram());
        $student->setAcademicYear($dto->getAcademicYear());
        $student->setRollNo($dto->getRollNo());
        $student->setYear($dto->getYear());
        $student->setRoomNo($dto->getRoomNo());
        $student->setParentNo($dto->getParentNo());
        
        if ($dto->getStatus() !== null) {
            $user->setStatus($dto->getStatus() ? UserStatus::ACTIVE : UserStatus::INACTIVE);
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

    public function getStudentsForExport(
        User $user,
        array $studentIds = [],
        ?int $academicYearId = null,
        ?int $programId = null
    ): array {
        $ids = array_values(array_unique(array_filter(array_map('intval', $studentIds), static fn ($id) => $id > 0)));

        $queryBuilder = $this->em->getRepository(Student::class)->createQueryBuilder('s')
            ->innerJoin('s.user', 'u')
            ->where('u.gender = :gender')
            ->setParameter('gender', $user->getGender())
            ->orderBy('u.name', 'ASC');

        if (!empty($ids)) {
            $queryBuilder
                ->andWhere('s.id IN (:studentIds)')
                ->setParameter('studentIds', $ids);
        }

        if ($academicYearId !== null && $academicYearId > 0) {
            $queryBuilder
                ->andWhere('IDENTITY(s.academicYear) = :academicYearId')
                ->setParameter('academicYearId', $academicYearId);
        }

        if ($programId !== null && $programId > 0) {
            $queryBuilder
                ->andWhere('IDENTITY(s.program) = :programId')
                ->setParameter('programId', $programId);
        }

        return $queryBuilder->getQuery()->getResult();
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
                            $queryBuilder->expr()->eq('s.rollNo', ':rollNo')
                        )
                    )
                    ->setParameter('search', '%' . $search . '%')
                    ->setParameter('rollNo', (int) $search);
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

    public function getStudentAccessIssue(User $user): ?string
    {
        if (!UserRole::isStudent($user->getRole()->value)) {
            return null;
        }

        $student = $this->em->getRepository(Student::class)->findOneBy(['user' => $user]);
        if (!$student) {
            return 'student_record_missing';
        }

        $academicYear = $student->getAcademicYear();
        if (!$academicYear instanceof AcademicYear) {
            return 'academic_year_missing';
        }

        if (!$academicYear->getStatus()) {
            return 'academic_year_inactive';
        }

        return null;
    }

    public function isStudentAcademicYearActive(User $user): bool
    {
        return $this->getStudentAccessIssue($user) === null;
    }

    public function getStudentById(int $studentId): ?Student
    {
        return $this->em->getRepository(Student::class)->find($studentId);
    }

    public function shiftStudentsCurrentYearByAcademicBatch(
        User $actor,
        AcademicYear $academicYear,
        bool $promoteCurrentYear = true,
        bool $deactivateExceeded = true
    ): array {
        $queryBuilder = $this->em->getRepository(Student::class)->createQueryBuilder('s')
            ->innerJoin('s.user', 'u')
            ->where('s.academicYear = :academicYear')
            ->andWhere('u.status = :activeStatus')
            ->setParameter('academicYear', $academicYear)
            ->setParameter('activeStatus', UserStatus::ACTIVE);

        if (!UserRole::isSuperAdmin($actor->getRole()->value)) {
            $queryBuilder
                ->andWhere('u.gender = :gender')
                ->setParameter('gender', $actor->getGender());
        }

        /** @var Student[] $students */
        $students = $queryBuilder->getQuery()->getResult();

        $stats = [
            'eligible_students' => count($students),
            'promoted_students' => 0,
            'shifted_students' => 0,
            'exceeded_students' => 0,
            'deactivated_students' => 0,
            'unchanged_students' => 0,
            'remaining_active_students' => 0,
            'academic_year_deactivated' => false,
        ];

        $now = new DateTime();

        foreach ($students as $student) {
            $changed = false;
            $duration = max(1, $student->getProgram()->getDuration());
            $currentYear = $student->getYear();

            if ($currentYear > $duration) {
                $stats['exceeded_students']++;
                if ($deactivateExceeded && $student->getUser()->getStatus() !== UserStatus::INACTIVE) {
                    $student->getUser()->setStatus(UserStatus::INACTIVE);
                    $student->setUpdatedAt($now);
                    $stats['deactivated_students']++;
                    $stats['shifted_students']++;
                    continue;
                }

                $stats['unchanged_students']++;
                continue;
            }

            if (!$promoteCurrentYear) {
                $stats['unchanged_students']++;
                continue;
            }

            $nextYear = $currentYear + 1;
            if ($nextYear > $duration) {
                $stats['exceeded_students']++;
                if ($deactivateExceeded && $student->getUser()->getStatus() !== UserStatus::INACTIVE) {
                    $student->getUser()->setStatus(UserStatus::INACTIVE);
                    $student->setUpdatedAt($now);
                    $stats['deactivated_students']++;
                    $stats['shifted_students']++;
                    continue;
                }

                $stats['unchanged_students']++;
                continue;
            }

            $student->setYear($nextYear);
            $stats['promoted_students']++;
            $changed = true;

            if ($changed) {
                $student->setUpdatedAt($now);
                $stats['shifted_students']++;
            } else {
                $stats['unchanged_students']++;
            }
        }

        $this->em->flush();

        $remainingActiveStudents = (int) $this->em->getRepository(Student::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->innerJoin('s.user', 'u')
            ->where('s.academicYear = :academicYear')
            ->andWhere('u.status = :activeStatus')
            ->setParameter('academicYear', $academicYear)
            ->setParameter('activeStatus', UserStatus::ACTIVE)
            ->getQuery()
            ->getSingleScalarResult();

        $stats['remaining_active_students'] = $remainingActiveStudents;

        if ($remainingActiveStudents === 0 && $academicYear->getStatus()) {
            $academicYear->setStatus(false);
            $this->em->flush();
            $stats['academic_year_deactivated'] = true;
        }

        return $stats;
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
