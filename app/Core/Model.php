<?php

namespace App\Core;

use PDO;


class Model
{
    protected $table = null;
    protected $fetchMode = null;
    
    public function __construct(public readonly PDO $conn)
    {
        $this->fetchMode = $this->conn::FETCH_ASSOC;
        $this->init();
    }

    /**
     * Factory pattern to obtain a database connection
     */
    public static function getConnection(PDO $conn)
    {
        return new static($conn);
    }

    private function init()
    {
    }

    protected function _set_table(string $tableName)
    {
        $this->table = $tableName;
    }

    public function save()
    {
    }

    public function fetchOne()
    {
    }

    public function update()
    {
    }
    
    public function delete()
    { 
    }
}

