<?php

namespace App;

use App\NotificationSenders\EmailNotification;
use App\NotificationSenders\SmsNotification;
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

        // Try to do this damn job. For you SnappDoctor
        $decodedData = QueueNotificationItem::fromQueueBody($message->getBody());

        try {
            // Check if the data is not valid then run fail method
            if (!$decodedData->idValid()) {
                $this->fail($message, $decodedData);
                return;
            }

            // Get Notification sender
            $notificationSender = match ($decodedData->getType()) {
                'email' => new EmailNotification(),
                'sms' => new SmsNotification()
            };

            // Send notification
            $notificationSender
                ->name($decodedData->getName())
                ->target($decodedData->getTo())
                ->message($decodedData->getMessage())
                ->send();

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

        echo "SUCCESS: Message for \"" . $decodedData->getName() . "\" sent.\n";
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

        echo "FAIL: Whoops! Message for \"" . $decodedData->getName() . "\" failed.\n";
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
            'name' => $decodedData->getName(),
            'message' => $decodedData->getMessage(),
            'type' => NotificationLogType::getFromTypeInQueue($decodedData->getType())->value,
            'target' => $decodedData->getTo(),
            'status' => $success ? NotificationLogStatus::SUCCESS->value : NotificationLogStatus::FAIL->value,
            'sent_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}