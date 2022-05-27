<?php

namespace App\NotificationSenders;

interface NotificationInterface
{
    public function getName(): string;
    public function getMessage(): string;
    public function getTarget(): string;
    public function name(string $name): BaseNotification;
    public function message(string $message): BaseNotification;
    public function target(string $target): BaseNotification;
    public function send(): bool;
}
