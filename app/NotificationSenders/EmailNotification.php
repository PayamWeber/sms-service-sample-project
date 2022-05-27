<?php

namespace App\NotificationSenders;

class EmailNotification extends BaseNotification
{
    /**
     * @return bool
     */
    public function send(): bool
    {
//        mail(
//            $this->getTarget(),
//            $this->getName(),
//            $this->getMessage()
//        );

        return true;
    }
}
