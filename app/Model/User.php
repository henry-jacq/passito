<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use App\Core\Session;
use App\Core\Database;

class User
{
    public $id;
    private $conn;
    protected $length = 32;
    protected $table = 'users';

    public function __construct(
        private readonly Database $db,
        private readonly Session $session,
    ) {
        $this->db->setTable($this->table);

        if (!$this->conn) {
            $this->conn = $this->db->getDB();
        }
    }

    public function create(array $data)
    {
        try {
            if ($result = $this->db->insert($data)) {
                return $result;
            }

            return false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Return user data if it exists in the DB
     */
    public function exists(string $data): array|bool
    {
        $query = "SELECT * FROM $this->table WHERE `email` = '{$data}'";

        $result = $this->db->rows($query);

        if (count($result) > 1) {
            throw new Exception('Duplicate User Entry Found!');
        }

        return empty($result) ? false : $result[0];
    }

    public function getUser()
    {
        if (!isset($this->id)) {
            $this->id = $this->session->get('user');
        }
        return $this->db->getRowById($this->id);
    }

    public function getID()
    {
        return $this->id;
    }

    public function getUserById(int $uid)
    {
        return $this->db->run("SELECT * FROM auth WHERE id = ?", [$uid])->fetch();
    }

    public function getByEmail(string $email)
    {
        $query = "SELECT * FROM $this->table WHERE `email` = ?'";

        if ($this->validateEmail($email)) {
            return $this->db->run($query, [$email]);
        }

        return false;
    }

    public function validateEmail(string $email)
    {
        return filterEmail($email);
    }

    /**
     * Sanitize the username according to the app needs
     */
    public function validateUsername(string $username)
    {
        // Replace whitespace with underscore
        $username = str_replace(' ', '_', trim($username));

        return strtolower($username);
    }
}
