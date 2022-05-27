<?php

namespace App\Database\Connections;

interface ConnectionInterface
{
    public function db();
    public function insert(string $table, array $attributes): bool;
}