<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    abstract public function print(): void;
}