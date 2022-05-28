<?php

namespace App\NotificationSenders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\KavenegarApi;

class KavenegarNotification extends BaseNotification
{
    /**
     * @return bool
     * @throws GuzzleException
     */
    public function send(): bool
    {
        try {
            $api = new KavenegarApi("API Key");
            $sender = "10004346";
            $message = $this->getMessage();
            $receptor = [$this->getTarget()];
            $api->Send($sender, $receptor, $message);
        } catch (ApiException|HttpException $e) {
            // log the error
            return false;
        }

        return true;
    }
}
