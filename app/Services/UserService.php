<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Session;
use App\Entity\Hostel;
use App\Enum\UserRole;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly Session $session,
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

    public function getWardens(): array
    {
        return $this->em->getRepository(User::class)->findBy(['role' => UserRole::ADMIN]);
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

        $hostel = $this->em->getRepository(Hostel::class)->find($data['hostel_no']);
        
        $student = new Student();
        $student->setUser($user);
        $student->setHostel($hostel);
        $student->setDigitalId($data['digital_id']);
        $student->setYear($data['year']);
        $student->setBranch($data['branch']);
        $student->setDepartment($data['department']);
        $student->setRoomNo($data['room_no']);
        $student->setParentNo($data['parent_no']);
        $student->setStatus(true);
        $student->setUpdatedAt(new DateTime());
        
        $this->em->persist($student);
        $this->em->flush();
        
        return $student;
    }

    public function getStudents(): array
    {
        return $this->em->getRepository(Student::class)->findAll();
    }

}
