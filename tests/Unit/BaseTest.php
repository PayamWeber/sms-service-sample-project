<?php

namespace Unit;

use App\Database\Connections\MysqlConnection;
use App\Database\Migrations\MigrationManager;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!file_exists(__DIR__ . '/../../.env.testing')) {
            die("\n please make a .env.testing file\n");
        }

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../', '.env.testing');
        $dotenv->load();

        if (method_exists($this, 'refreshDB')) {
            $this->refreshDB();
        }
    }

    protected function assertDatabaseCount(string $table, int $count)
    {
        $connection = new MysqlConnection();
        $this->assertEquals(
            $count,
            $connection->db()->query("SELECT count(*) as count FROM $table;")->fetch()['count']
        );
    }
}
