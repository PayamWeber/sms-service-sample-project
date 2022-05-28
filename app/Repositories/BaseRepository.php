<?php

namespace App\Repositories;

use App\Database\Connections\ConnectionInterface;
use App\Database\Connections\MysqlConnection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var ConnectionInterface
     */
    protected ConnectionInterface $connection;

    /**
     * @var string
     */
    protected string $table = '';

    public function __construct()
    {
        $this->connection = new MysqlConnection();
    }

    /**
     * @param array $attributes
     * @return void
     */
    public function create(array $attributes)
    {
        $this->connection->insert($this->table, $attributes);
    }

    /**
     * @param int|string $id
     * @return bool|array
     */
    public function find(int|string $id): bool|array
    {
        return $this->connection->select(
            $this->table,
            ['*'],
            [
                ['id', '=', $id]
            ]
        );
    }
}