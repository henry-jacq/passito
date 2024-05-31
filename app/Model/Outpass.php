<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use App\Core\Session;
use App\Core\Database;

class Outpass
{
    public $id;
    private $conn;
    protected $length = 32;
    protected $table = 'outpass_requests';

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

    public function getOutpass()
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
    
}
