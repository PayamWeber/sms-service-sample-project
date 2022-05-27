<?php

namespace App\Database\Migrations;

use App\Database\Connections\ConnectionInterface;
use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\Enums\NotificationLogType;

class MigrationManager
{
    /**
     * @var ConnectionInterface
     */
    private ConnectionInterface $connection;

    /**
     * @var bool
     */
    private bool $printActions = false;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function runMigrations()
    {
        $this->notificationLogs();
    }

    /**
     * @return void
     */
    protected function notificationLogs(): void
    {
        // Notification Logs
        $defaultType = NotificationLogType::EMAIL->value;
        $defaultStatus = NotificationLogStatus::SUCCESS->value;
        $tableName = 'notification_logs';
        $this->connection->db()->exec(
            "
            DROP TABLE IF EXISTS `$tableName`;
            CREATE TABLE `$tableName` (
              `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `type` tinyint NOT NULL DEFAULT '$defaultType',
              `status` tinyint NOT NULL DEFAULT '$defaultStatus',
              `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `sent_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            "
        );

        // Print alert in console if its needed
        if($this->printActions){
            echo "Table '$tableName' made.\n";
        }
    }

    /**
     * @param bool $printActions
     */
    public function printActions(bool $printActions): void
    {
        $this->printActions = $printActions;
    }
}