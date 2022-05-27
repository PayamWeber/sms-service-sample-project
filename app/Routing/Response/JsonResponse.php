<?php

namespace App\Routing\Response;

class JsonResponse extends Response
{
    /**
     * @var array
     */
    protected array $value;

    /**
     * @param array $value
     * @return static
     */
    public static function from(array $value): static
    {
        $jsonResponse = new static();
        $jsonResponse->value = $value;

        return $jsonResponse;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        header('Content-Type: Application/Json');
        return json_encode($this->value, JSON_UNESCAPED_UNICODE);
    }
}