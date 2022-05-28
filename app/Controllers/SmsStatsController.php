<?php

namespace App\Controllers;

use App\Exceptions\AuthenticationException;
use App\Exceptions\ValidationException;
use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\NotificationLogRepository;
use App\Routing\Response\JsonResponse;
use App\Services\RabbitMessageSender;

class SmsStatsController extends Controller
{
    /**
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function history(): JsonResponse
    {
        $this->auth();

        $wheres = $this->applyFilters();

        $items = (new NotificationLogRepository())->latest(
            $wheres,
            min(request()->get('limit', 10), 100)
        );

        return JsonResponse::from([
            'status' => true,
            'data' => [
                'items' => $items
            ]
        ]);
    }

    /**
     * @return array
     */
    protected function applyFilters(): array
    {
        $wheres = [];

        if ($value = request()->get('number', '')) {
            $wheres[] = ['target', 'LIKE', "%$value%"];
        }

        if ($value = request()->get('message', '')) {
            $wheres[] = ['message', 'LIKE', "%$value%"];
        }

        if ($value = request()->get('status', '')) {
            $wheres[] = ['status', '=', $value];
        }

        return $wheres;
    }
}