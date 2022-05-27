<?php

namespace Unit\Repositories;

use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\Enums\NotificationLogType;
use App\Repositories\NotificationLogRepository;
use Carbon\Carbon;
use Unit\BaseTest;
use Unit\RefreshDatabase;

class NotificationLogRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    public function testCreate()
    {
        $repository = new NotificationLogRepository();

        $repository->create([
            'name' => 'test',
            'message' => 'test',
            'type' => NotificationLogType::getFromTypeInQueue('sms')->value,
            'target' => '984987987',
            'status' => NotificationLogStatus::SUCCESS->value,
            'sent_at' => Carbon::now()->toDateTimeString(),
        ]);

        $this->assertDatabaseCount('notification_logs', 1);
    }

    public function testCreateNotWorking()
    {
        $this->expectException(\PDOException::class);

        $repository = new NotificationLogRepository();

        $repository->create([
            'fake_field' => 'test',
            'name' => 'test',
            'message' => 'test',
            'type' => NotificationLogType::getFromTypeInQueue('sms')->value,
            'target' => '984987987',
            'status' => NotificationLogStatus::SUCCESS->value,
            'sent_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
