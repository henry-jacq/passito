<?php

namespace App\Services;

use App\Core\Session;
use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class AuthService
{
    public function __construct(
        private readonly Session $session,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    /**
     * Authenticate user with provided credentials
     */
    public function login(array $data): bool|User
    {
        // Fetch user by email
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        // Validate user existence and password
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            return false; // Authentication failed
        }

        $userRole = $user->getRole()->value;

        // Check if the user's role is valid
        if (!UserRole::isValidRole($userRole)) {
            return false; // Invalid role
        }

        // Authentication successful, initialize session
        // Set user and role in the session
        $this->session->put('role', $userRole);
        $this->session->put('user', $user->getId());

        return $user;
    }

    /**
     * Logout user from the session
     */
    public function logout()
    {
        $this->session->forget('user');
        $this->session->forget('role');
        $this->session->forget('userData');
        $this->session->regenerate();
    }
    
    /**
     * Check if user is authenticated
     */
    public function isAuthenticated()
    {
        if ($this->session->get('user') !== null) {
            return true;
        }
        return false;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        if (UserRole::isAdministrator($this->session->get('role'))) {
            return true;
        }
        return false;
    }

    // TODO: Add more methods for password reset, session management, 2FA, rbac etc.
}