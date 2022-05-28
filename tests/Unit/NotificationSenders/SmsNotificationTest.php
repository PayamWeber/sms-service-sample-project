<?php

namespace Unit\NotificationSenders;

use App\NotificationSenders\KavenegarNotification;
use Unit\BaseTest;
use Unit\RefreshDatabase;

class SmsNotificationTest extends BaseTest
{
    use RefreshDatabase;

    public function testSend()
    {
        $result = (new KavenegarNotification())
            ->name('test')
            ->target('test@test.com')
            ->message('test')
            ->send();

        $this->assertEquals(true, $result);
    }
}
