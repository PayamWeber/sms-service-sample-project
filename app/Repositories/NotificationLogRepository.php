<?php

namespace App\Repositories;

use App\Database\Connections\ConnectionInterface;
use App\Database\Connections\MysqlConnection;

class NotificationLogRepository implements BaseRepository
{
    /**
     * @var ConnectionInterface
     */
    protected ConnectionInterface $connection;

    public function __construct()
    {
        $this->connection = new MysqlConnection();
    }

    public function create(array $attributes)
    {
        $this->connection->insert('notification_logs', $attributes);
    }
}