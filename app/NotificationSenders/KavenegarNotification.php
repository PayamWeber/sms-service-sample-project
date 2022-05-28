<?php

namespace App\NotificationSenders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class KavenegarNotification extends BaseNotification
{
    /**
     * @return bool
     * @throws GuzzleException
     */
    public function send(): bool
    {
        $client = new Client();
        $client->post('https://payamjafari.ir/', [
            'form_params' => [
                'mobile' => $this->getTarget(),
                'message' => $this->getName() . ' ' . $this->getMessage(),
            ]
        ]);

        return true;
    }
}
