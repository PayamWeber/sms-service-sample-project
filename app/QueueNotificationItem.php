<?php

namespace App;

use JetBrains\PhpStorm\Pure;

class QueueNotificationItem
{

    /**
     * @var string
     */
    protected string $message = '';

    /**
     * @var string
     */
    protected string $to = '';

    /**
     * Make instance from queue data
     *
     * @param string $body
     * @return static
     */
    public static function fromQueueBody(string $body): static
    {
        $decodedData = json_decode($body, true);

        $instance = new static();
        $instance->setMessage($decodedData['message'] ?? '');
        $instance->setTo($decodedData['to'] ?? ($decodedData['mailto:to']));

        return $instance;
    }

    /**
     * Check if the data is valid or not
     *
     * @return bool
     */
    #[Pure] public function idValid(): bool
    {
        return $this->getMessage() && $this->getTo();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(string $to): void
    {
        $this->to = $to;
    }
}