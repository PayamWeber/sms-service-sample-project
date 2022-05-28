<?php

namespace App\Services;

class Request
{
    /**
     * @var self|null
     */
    protected static ?self $instance = null;

    /**
     * @var array
     */
    protected array $requestBody = [];

    protected function __construct()
    {
        $this->requestBody = $_REQUEST;
    }

    /**
     * This is Singleton
     * @return $this
     */
    public static function getInstance(): self
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        } else {
            return self::$instance = new self;
        }
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $name, mixed $default = null): mixed
    {
        return $this->requestBody[$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     * @return string|null
     */
    public function header(string $name, mixed $default = null): ?string
    {
        return $_SERVER['HTTP_' . strtoupper($name)] ?? $default;
    }
}