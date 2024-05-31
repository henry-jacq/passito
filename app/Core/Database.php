<?php

namespace App\Core;

use PDO;

class Database
{
    protected $table = null;
    protected $fetchMode = null;

    public function __construct(public readonly PDO $conn)
    {
        $this->fetchMode = $this->conn::FETCH_ASSOC;
    }

    /**
     * Factory pattern for getting a database connection
     */
    public static function getConnection(PDO $pdo)
    {
        return new static($pdo);
    }

    /**
     * Return a PDO statement object
     */
    public function run($sql, $args = [])
    {
        if (empty($args)) {
            return $this->conn->query($sql);
        }

        $stmt = $this->conn->prepare($sql);

        //check if args is associative or sequential?
        $is_assoc = (array() === $args) ? false : array_keys($args) !== range(0, count($args) - 1);
        if ($is_assoc) {
            foreach ($args as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
            $stmt->execute();
        } else {
            $stmt->execute($args);
        }

        return $stmt;
    }

    public function setTable(string $tableName)
    {
        $this->table = $tableName;
    }

    public function getDB()
    {
        return $this->conn;
    }

    public function raw($sql)
    {
        $this->conn->query($sql);
    }

    public function row($sql, $args = [], $fetchMode = null)
    {
        if ($fetchMode === null) {
            $fetchMode = $this->fetchMode;
        }
        return $this->run($sql, $args)->fetch($fetchMode);
    }

    public function rows($sql, $args = [], $fetchMode = null)
    {
        if ($fetchMode === null) {
            $fetchMode = $this->fetchMode;
        }
        return $this->run($sql, $args)->fetchAll($fetchMode);
    }

    public function getRowById($id, $param = 'id', $fetchMode = null)
    {
        if ($fetchMode === null) {
            $fetchMode = $this->fetchMode;
        }
        return $this->run("SELECT * FROM $this->table WHERE $param = ?", [$id])->fetch($fetchMode);
    }

    public function getCount($sql, $args = [])
    {
        return $this->run($sql, $args)->rowCount();
    }

    /**
     * Get primary key of last inserted record
     */
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function insert(array $data)
    {
        // Add columns into comma separated string
        $columns = implode(',', array_keys($data));

        // Get values
        $values = array_values($data);

        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));

        // Convert array into comma separated string
        $placeholders = implode(',', array_values($placeholders));

        $this->run("INSERT INTO $this->table ($columns) VALUES ($placeholders)", $values);

        return $this->lastInsertId();
    }

    public function update(array $data, array $where): int
    {
        //collect the values from data and where
        $values = [];

        //setup fields
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
            $values[] = $value;
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $values[] = $value;
            $i++;
        }

        $stmt = $this->run("UPDATE $this->table SET $fieldDetails WHERE $whereDetails", $values);

        return $stmt->rowCount();
    }

    public function delete(array $where, $limit = 1)
    {
        //collect the values from collection
        $values = array_values($where);

        //setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $limit = "LIMIT $limit";
        }

        $stmt = $this->run("DELETE FROM $this->table WHERE $whereDetails $limit", $values);

        return $stmt->rowCount();
    }

    public function deleteById($id)
    {
        $stmt = $this->run("DELETE FROM $this->table WHERE id = ?", [$id]);

        return $stmt->rowCount();
    }
}
