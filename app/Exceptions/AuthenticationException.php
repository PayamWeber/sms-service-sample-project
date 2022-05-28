<?php

namespace App\Exceptions;

use App\Routing\Response\JsonResponse;

class AuthenticationException extends BaseException
{
    protected $code = 401;

    public function print(): void
    {
        http_response_code($this->getCode());
        echo JsonResponse::from([
            'status' => false,
            'message' => 'unauthenticated'
        ])->render();
    }
}