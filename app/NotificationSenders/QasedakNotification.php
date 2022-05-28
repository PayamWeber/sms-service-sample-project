<?php

namespace App\NotificationSenders;

class QasedakNotification extends BaseNotification
{
    /**
     * @return bool
     */
    public function send(): bool
    {
        // send the sms

        return true;
    }
}
