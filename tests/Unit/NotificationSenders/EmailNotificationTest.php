<?php

namespace Unit\NotificationSenders;

use App\NotificationSenders\EmailNotification;
use Unit\BaseTest;
use Unit\RefreshDatabase;

class EmailNotificationTest extends BaseTest
{
    use RefreshDatabase;

    public function testSend()
    {
        $result = (new EmailNotification())
            ->name('test')
            ->target('test@test.com')
            ->message('test')
            ->send();

        $this->assertEquals(true, $result);
    }
}
