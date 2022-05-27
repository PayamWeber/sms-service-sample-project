<?php

namespace App\Exceptions;

class NotFoundException extends BaseException
{
    protected $code = 404;

    public function print(): void
    {
        http_response_code($this->getCode());
        echo "NOT FOUND!";
    }
}