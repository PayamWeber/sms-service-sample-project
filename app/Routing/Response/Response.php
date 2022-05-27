<?php

namespace App\Routing\Response;

abstract class Response
{
    abstract public function render(): string;
}