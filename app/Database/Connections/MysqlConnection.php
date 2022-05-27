<?php

namespace App\Database\Connections;

use PDO;

class MysqlConnection implements ConnectionInterface
{
    /**
     * @var PDO
     */
    protected PDO $db;

    public function __construct()
    {
        $this->initConnection();
    }

    public function db(): PDO
    {
        return $this->db;
    }

    /**
     * @param string $table
     * @param array $attributes
     * @return bool
     */
    public function insert(string $table, array $attributes): bool
    {
        $columnNames = implode(', ', array_keys($attributes));
        $placeholders = implode(', ', array_fill(0, count($attributes), '?'));
        $insert = $this->db()->prepare(
            "INSERT INTO $table ($columnNames) VALUES ($placeholders)"
        );

        foreach (array_values($attributes) as $key => $value){
            $insert->bindValue($key+1, $value);
        }

        if ($insert->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return void
     */
    protected function initConnection(): void
    {
        $host = $_ENV['DB_HOST'] ?? '';
        $port = $_ENV['DB_PORT'] ?? '';
        $database = $_ENV['DB_NAME'] ?? '';
        $username = $_ENV['DB_USERNAME'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $address = "mysql:host=$host;port=$port;dbname=$database;";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $this->db = new PDO($address, $username, $password, $options);
    }
}