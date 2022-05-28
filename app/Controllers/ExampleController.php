<?php

namespace App\Controllers;

use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidationException;
use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\NotificationLogRepository;
use App\Routing\Response\JsonResponse;
use App\Services\RabbitMessageSender;

class ExampleController extends Controller
{
    public function sample($example_parameter2, $example_parameter1): JsonResponse
    {
        return JsonResponse::from([
            'status' => true,
            'data' => [
                'test1' => $example_parameter1,
                'test2' => $example_parameter2,
            ]
        ]);
    }
}