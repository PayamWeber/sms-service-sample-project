<?php

namespace Unit\NotificationSenders;

use App\NotificationSenders\KavenegarNotification;
use App\NotificationSenders\QasedakNotification;
use Unit\BaseTest;
use Unit\RefreshDatabase;

class SmsNotificationTest extends BaseTest
{
    use RefreshDatabase;

    public function testKavenegarSend()
    {
        $result = (new KavenegarNotification())
            ->target('098989898')
            ->message('test')
            ->send();

        $this->assertEquals(true, $result);
    }

    public function testQasedakSend()
    {
        $result = (new QasedakNotification())
            ->target('098989898')
            ->message('test')
            ->send();

        $this->assertEquals(true, $result);
    }
}
