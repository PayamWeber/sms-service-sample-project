<?php

namespace Unit\NotificationSenders;

use App\NotificationSenders\SmsNotification;
use Unit\BaseTest;
use Unit\RefreshDatabase;

class SmsNotificationTest extends BaseTest
{
    use RefreshDatabase;

    public function testSend()
    {
        $result = (new SmsNotification())
            ->name('test')
            ->target('test@test.com')
            ->message('test')
            ->send();

        $this->assertEquals(true, $result);
    }
}
