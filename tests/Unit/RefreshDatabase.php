<?php

namespace Unit;

use App\Database\Connections\MysqlConnection;
use App\Database\Migrations\MigrationManager;
use App\Database\Seeders\SeederManager;

trait RefreshDatabase
{
    protected function refreshDB()
    {
        $connection = new MysqlConnection();

        $migrationManager = new MigrationManager($connection);

        $migrationManager->runMigrations();

        (new SeederManager())->runSeeders();
    }
}