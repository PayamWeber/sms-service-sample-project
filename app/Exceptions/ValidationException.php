<?php

namespace App\Exceptions;

use App\Routing\Response\JsonResponse;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class ValidationException extends BaseException
{
    protected $code = 423;

    /**
     * @var array
     */
    protected array $errors = [];

    public function __construct(array $errors = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('', $code, $previous);

        $this->errors = $errors;
    }

    /**
     * @return void
     */
    public function print(): void
    {
        http_response_code($this->getCode());
        echo JsonResponse::from([
            'status' => false,
            'errors' => $this->errors
        ])->render();
    }
}