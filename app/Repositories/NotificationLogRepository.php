<?php

namespace App\Repositories;

class NotificationLogRepository extends BaseRepository
{
    protected string $table = 'notification_logs';

    protected string $primaryDateField = 'sent_at';
}