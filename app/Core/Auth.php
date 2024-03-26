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
