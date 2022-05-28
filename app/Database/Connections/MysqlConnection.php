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
     * @param string $table
     * @param array $columns
     * @param array $wheres
     * @param array $orderBy
     * @param int $limit
     * @return bool|array
     */
    public function select(string $table, array $columns = ['*'], array $wheres = [], array $orderBy = [], int $limit = 10): bool|array
    {
        $columns = implode(',', $columns);

        if($wheres){
            $whereValues = [];
            foreach ($wheres as &$where){
                $whereValues[] = $where[2];
                $where = $where[0] . ' ' . $where[1] . ' ?';
            }
        }

        if($orderBy){
            foreach ($orderBy as $name => &$order){
                $order = $name . ' ' . $order;
            }
        }

        $whereString = $wheres ? 'WHERE ' . implode(' AND ', $wheres) : '';
        $orderString = $orderBy ? 'ORDER BY ' . implode(', ', $orderBy) : '';
        $limitString = $limit ? 'LIMIT ' . $limit : '';

        $statement = $this->db()->prepare(
            "SELECT $columns FROM $table $whereString $orderString $limitString"
        );

        if($wheres){
            foreach ($whereValues as $key => $value){
                $statement->bindValue($key+1, $value);
            }
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_NAMED);
    }

    /**
     * @param string $table
     * @param array $columns
     * @param array $wheres
     * @param array $orderBy
     * @return bool|array
     */
    public function selectFirst(string $table, array $columns = ['*'], array $wheres = [], array $orderBy = []): bool|array
    {
        $found = $this->select($table, $columns, $wheres, $orderBy, 10);

        return $found[0] ?? false;
    }

    /**
     * @param string $query
     * @return array|bool
     */
    public function raw(string $query): array|bool
    {
        return $this->db->query($query)->fetchAll();
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