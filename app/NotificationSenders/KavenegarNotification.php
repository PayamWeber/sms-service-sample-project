<?php

namespace App\NotificationSenders;

use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi;

class KavenegarNotification extends BaseNotification
{
    /**
     * @return bool
     */
    public function send(): bool
    {
        try {
            $api = new KavenegarApi($_ENV['KAVENEGAR_API_KEY'] ?? '');
            $sender = $_ENV['KAVENEGAR_SENDER'] ?? '';
            $message = $this->getMessage();
            $receptor = [$this->getTarget()];

//            $api->Send($sender, $receptor, $message);
        } catch (ApiException|HttpException $e) {
            // log the error
            return false;
        }

        return true;
    }
}
