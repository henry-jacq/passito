<?php

namespace App\Core;

use App\Model\User;
use App\Core\Session;

class Auth
{
    public function __construct(
        private readonly User $user,
        private readonly Session $session,
    ) {
    }

    /**
     * Register user
     */
    public function register(array $credentials)
    {
    }

    /**
     * Login user
     */
    public function login(array $credentials)
    {
        $email = $credentials['user'];
        $pass = $credentials['password'];

        $result = $this->user->exists($email);
        
        if ($result !== false) {
            if ($result['password'] !== $pass) {
                return false;
            }
            // Do hashing
            $this->session->put('user', $result['id']);
            $this->user->id = $this->session->get('user');
            return $result;
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
