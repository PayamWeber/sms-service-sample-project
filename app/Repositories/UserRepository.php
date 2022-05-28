<?php

namespace App\Repositories;

class UserRepository extends BaseRepository
{
    protected string $table = 'users';

    /**
     * @param string $username
     * @return bool|array
     */
    public function findByUserName(string $username): bool|array
    {
        return $this->connection->selectFirst($this->table, ['*'], [
            ['username', '=', $username]
        ]);
    }
}