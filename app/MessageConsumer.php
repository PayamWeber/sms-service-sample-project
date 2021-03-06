<?php

namespace App;

use App\NotificationSenders\EmailNotification;
use App\NotificationSenders\KavenegarNotification;
use App\NotificationSenders\QasedakNotification;
use App\Repositories\Enums\NotificationLogStatus;
use App\Repositories\Enums\NotificationLogType;
use App\Repositories\NotificationLogRepository;
use Carbon\Carbon;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class MessageConsumer
{
    /**
     * @param AMQPMessage $message
     * @return void
     */
    public function process(AMQPMessage $message)
    {
        // If message has word "quit" then cancel the consumer
        if ($message->getBody() === 'quit') {
            $message->getChannel()->basic_cancel($message->getConsumerTag());
            return;
        }

        // Try to do this damn job
        $decodedData = QueueNotificationItem::fromQueueBody($message->getBody());

        try {
            // Check if the data is not valid then run fail method
            if (!$decodedData->idValid()) {
                $this->fail($message, $decodedData);
                return;
            }

            // Get Notification sender
            $notificationSender = match ($_ENV['NOTIFICATION_DRIVER'] ?? 'kavenegar') {
                'kavenegar' => new KavenegarNotification(),
                'qasedak' => new QasedakNotification(),
            };

            // Send notification
            $send = $notificationSender
                ->target($decodedData->getTo())
                ->message($decodedData->getMessage())
                ->send();

            if(!$send){
                $this->fail($message, $decodedData);
                return;
            }

            $this->success($message, $decodedData);
        } catch (Throwable $exception) {
            $this->fail($message, $decodedData);
            return;
        }
    }

    /**
     * @param AMQPMessage $message
     * @param QueueNotificationItem $decodedData
     * @return void
     */
    protected function success(AMQPMessage $message, QueueNotificationItem $decodedData)
    {
        $message->ack();

        $this->logInDatabase($decodedData);

        echo "SUCCESS: Message for \"" . $decodedData->getTo() . "\" sent.\n";
    }

    /**
     * @param AMQPMessage $message
     * @param QueueNotificationItem|null $decodedData
     * @return void
     */
    protected function fail(AMQPMessage $message, ?QueueNotificationItem $decodedData = null)
    {
        $message->ack();

        $this->logInDatabase($decodedData, false);

        echo "FAIL: Whoops! Message for \"" . $decodedData->getTo() . "\" failed.\n";
    }

    /**
     * @param QueueNotificationItem $decodedData
     * @param bool $success
     * @return void
     */
    protected function logInDatabase(QueueNotificationItem $decodedData, bool $success = true): void
    {
        $notificationLogRepo = new NotificationLogRepository();

        $notificationLogRepo->create([
            'message' => $decodedData->getMessage(),
            'target' => $decodedData->getTo(),
            'status' => $success ? NotificationLogStatus::SUCCESS->value : NotificationLogStatus::FAIL->value,
            'sent_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}