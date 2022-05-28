<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function create(array $attributes);
    public function find(int|string $id): bool|array;
}