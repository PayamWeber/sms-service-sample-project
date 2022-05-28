<?php

use App\Database\Connections\MysqlConnection;
use App\Database\Migrations\MigrationManager;

require_once __DIR__ . '/load.php';

$connection = new MysqlConnection();

$migrationManager = new MigrationManager($connection);

$migrationManager->printActions(true);
$migrationManager->runMigrations();