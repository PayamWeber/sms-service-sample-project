<?php

namespace App\Auth;

class Auth
{
    /**
     * @var self|null
     */
    protected static ?self $instance = null;

    /**
     * @var array
     */
    protected array $user;

    protected function __construct()
    {
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
     * @param array $userInformation
     * @return void
     */
    public function setUser(array $userInformation)
    {
        $this->user = $userInformation;
    }

    /**
     * @return array
     */
    public function user(): array
    {
        return $this->user;
    }
}