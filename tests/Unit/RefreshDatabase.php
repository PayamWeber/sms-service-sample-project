<?php

namespace Unit;

use App\Database\Connections\MysqlConnection;
use App\Database\Migrations\MigrationManager;

trait RefreshDatabase
{
    protected function refreshDB()
    {
        $connection = new MysqlConnection();

        $migrationManager = new MigrationManager($connection);

        $migrationManager->runMigrations();
    }
}