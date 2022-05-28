<?php

namespace App\Repositories;

class AccessTokenRepository extends BaseRepository
{
    protected string $table = 'access_tokens';

    /**
     * @param string|int $id
     * @return bool|array
     */
    public function findByUserId(string|int $id): bool|array
    {
        return $this->connection->selectFirst($this->table, ['*'], [
            ['user_id', '=', $id]
        ]);
    }

    /**
     * @param string $token
     * @return bool|array
     */
    public function findByToken(string $token): bool|array
    {
        return $this->connection->selectFirst($this->table, ['*'], [
            ['token', '=', hash('sha256', $token)]
        ]);
    }
}