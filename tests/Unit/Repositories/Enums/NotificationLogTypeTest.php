<?php

namespace Unit\Repositories\Enums;

use App\Repositories\Enums\NotificationLogType;
use Unit\BaseTest;

class NotificationLogTypeTest extends BaseTest
{
    public function testGetFromTypeInQueue()
    {
        $this->assertEquals(NotificationLogType::SMS, NotificationLogType::getFromTypeInQueue('sms'));
    }

    public function testGetFromTypeInQueueNotWorking()
    {
        $this->assertNotEquals(NotificationLogType::SMS, NotificationLogType::getFromTypeInQueue('fake'));
    }
}
