<?php

namespace App\Repositories\Enums;

enum NotificationLogType: int {
    case EMAIL = 1;
    case SMS = 2;

    /**
     * @param string $type
     * @return NotificationLogType|null
     */
    public static function getFromTypeInQueue(string $type): ?NotificationLogType
    {
        return match ($type){
            'email' => self::EMAIL,
            'sms' => self::SMS,
            default => null
        };
    }
}
