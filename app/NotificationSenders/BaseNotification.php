<?php

namespace App\NotificationSenders;

abstract class BaseNotification implements NotificationInterface
{

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var string
     */
    protected string $target;

    /**
     * @param string $message
     * @return BaseNotification
     */
    public function message(string $message): BaseNotification
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $target
     * @return BaseNotification
     */
    public function target(string $target): BaseNotification
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @return bool
     */
    abstract public function send(): bool;
}
