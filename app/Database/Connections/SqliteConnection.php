<?php

namespace App\Database\Connections;

use PDO;

class SqliteConnection extends MysqlConnection
{
    /**
     * @return void
     */
    protected function initConnection(): void
    {
        $file = $_ENV['DB_FILE'] ?? '';
        $address = "sqlite:$file";

        $this->db = new PDO($address);
    }
}