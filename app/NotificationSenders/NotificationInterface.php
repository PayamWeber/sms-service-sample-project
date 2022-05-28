<?php

namespace App\NotificationSenders;

interface NotificationInterface
{
    public function getMessage(): string;
    public function getTarget(): string;
    public function message(string $message): BaseNotification;
    public function target(string $target): BaseNotification;
    public function send(): bool;
}
