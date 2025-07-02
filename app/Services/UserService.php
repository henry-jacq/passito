<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Session;
use App\Enum\UserRole;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly Session $session,
        private readonly FacilityService $facility,
        private readonly EntityManagerInterface $em
    )
    {
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
        $user->setPassword(password_hash($data['contact'], PASSWORD_DEFAULT, ['cost' => 12]));
        $user->setContactNo($data['contact']);
        $user->setGender($data['gender']);
        $user->setRole($data['role'] ?? UserRole::USER);
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
            $this->em->remove($user);
            $this->em->flush();
            return true;
        }

        return false;
    }

    public function createStudent(array $data, User $user): Student|bool
    {
        // Restrict if user with email not found
        if (!$this->getUserByEmail($data['email']) === null) {
            return false;
        }

        $hostel = $this->facility->getHostelById($data['hostel_no']);
        $institution = $this->facility->getInstitutionById($data['institution']);
        
        $student = new Student();
        $student->setUser($user);
        $student->setInstitution($institution);
        $student->setHostel($hostel);
        $student->setDigitalId($data['digital_id']);
        $student->setYear($data['year']);
        $student->setBranch($data['branch']);
        $student->setCourse($data['course']);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo($data['parent_no']);
        $student->setStatus(true);
        $student->setUpdatedAt(new DateTime());
        
        $this->em->persist($student);
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
    
    /**
     * Get student by user
     */
    public function getStudentByUser(User $user): Student
    {
        return $this->em->getRepository(Student::class)->findOneBy(['user' => $user]);
    }
    
    /**
     * Remove student
     */
    public function removeStudent(int $studentId): bool
    {
        $student = $this->em->getRepository(Student::class)->find($studentId);

        if ($student) {
            $this->em->remove($student);
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
