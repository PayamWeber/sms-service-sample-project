<?php

namespace App\Controllers;

use App\Routing\Response\JsonResponse;

class SmsSendController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function send(): JsonResponse
    {
        return JsonResponse::from([
            'status' => true
        ]);
    }
}