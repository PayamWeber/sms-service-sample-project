<?php

use App\Database\Seeders\SeederManager;

require_once __DIR__ . '/load.php';

$migrationManager = new SeederManager();

$migrationManager->printActions(true);
$migrationManager->runSeeders();