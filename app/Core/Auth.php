<?php

namespace App\Core;

use App\Entity\User;
use App\Core\Session;
use Doctrine\ORM\EntityManagerInterface;

class Auth
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Session $session,
    ) {
    }

    /**
     * Login user
     */
    public function login(array $credentials)
    {
        $email = $credentials['email'];
        $pass = $credentials['password'];

        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user && password_verify($pass, $user->getPassword())) {
            $this->session->put('user', $user->getId());
            return $user;
        }

        return false;
    }

    /**
     * Logout user from the session
     */
    public function logout(): void
    {
        $this->session->forget('user');
    }

    /**
     * Check if the user is logged in or not
     */
    public function isAuthenticated(): bool
    {
        if ($this->session->get('user') !== null) {
            return true;
        }
        return false;
    }
}
