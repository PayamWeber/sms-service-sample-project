<?php

namespace App\Controllers;

use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidationException;
use App\Routing\Response\JsonResponse;
use App\Services\RabbitMessageSender;

class SmsSendController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException|ValidationException
     */
    public function send(): JsonResponse
    {
        $this->auth();
        $rabbitMessageSender = new RabbitMessageSender();
        list($number, $message) = $this->getAndValidateParameters();
        $rabbitMessageSender->send([
            'target' => $number,
            'message' => $message,
        ]);
        return JsonResponse::from([
            'status' => true
        ]);
    }

    /**
     * @return array
     * @throws ValidationException
     */
    protected function getAndValidateParameters(): array
    {
        if (!$number = arabic_persian_to_english(request()->get('number', ''))) {
            throw new ValidationException(['please enter number']);
        }
        if (!$message = request()->get('message', '')) {
            throw new ValidationException(['please enter message']);
        }

        return [$number, $message];
    }
}