<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Session;
use App\Enum\UserRole;
use InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly Session $session,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    public function createWarden(array $data): User|bool
    {
        // check if user with email exists
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if ($user) {
            return false;
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
}

