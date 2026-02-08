<?php

namespace App\Services;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class AuthService
{
    public function __construct(
        private readonly JwtService $jwt,
        private readonly EntityManagerInterface $em
    )
    {
    }
    
    /**
     * Authenticate user with provided credentials
     */
    public function login(array $data): bool|array
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

        $token = $this->jwt->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Authenticate user with LoginDto
     */
    public function loginWithDto(\App\Dto\LoginDto $dto): bool|array
    {
        // Fetch user by email
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $dto->getEmail()]);

        // Validate user existence and password
        if (!$user || !password_verify($dto->getPassword(), $user->getPassword())) {
            return false; // Authentication failed
        }

        $userRole = $user->getRole()->value;

        // Check if the user's role is valid
        if (!UserRole::isValidRole($userRole)) {
            return false; // Invalid role
        }

        $token = $this->jwt->createToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout user from the session
     */
    public function logout()
    {
        return;
    }
    
    /**
     * Check if user is authenticated
     */
    public function isAuthenticated()
    {
        $cookieName = $this->jwt->getCookieName();
        $token = $_COOKIE[$cookieName] ?? null;
        if (empty($token)) {
            return false;
        }

        return (bool) $this->jwt->decode($token);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        $cookieName = $this->jwt->getCookieName();
        $token = $_COOKIE[$cookieName] ?? null;
        if (empty($token)) {
            return false;
        }

        $payload = $this->jwt->decode($token);
        if (!$payload) {
            return false;
        }

        return UserRole::isAdministrator($payload['role'] ?? '');
    }

    // TODO: Add more methods for password reset, session management, 2FA, rbac etc.
}
