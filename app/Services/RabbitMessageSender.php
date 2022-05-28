<?php

namespace App\Services;

use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMessageSender
{
    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;

    /**
     * @var AbstractChannel|\PhpAmqpLib\Channel\AMQPChannel
     */
    protected AbstractChannel|\PhpAmqpLib\Channel\AMQPChannel $channel;

    public function __construct()
    {
        $this->initializeConnection();

        $this->channel = $this->getConnection()->channel();
    }

    /**
     * @param array $body
     * @return void
     */
    public function send(array $body)
    {
        list($queue, $exchange) = $this->declareQueueAndExchange();

        $message = new AMQPMessage(
            json_encode($body, JSON_UNESCAPED_UNICODE),
            [
                'content_type' => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );
        $this->getChannel()->basic_publish($message, $exchange);

        $this->closeChannelAndConnection();
    }

    /**
     * @return void
     */
    protected function initializeConnection(): void
    {
        try {
            $this->connection = new AMQPStreamConnection(
                $_ENV['RABBITMQ_HOST'] ?? '',
                $_ENV['RABBITMQ_PORT'] ?? '',
                $_ENV['RABBITMQ_USERNAME'] ?? '',
                $_ENV['RABBITMQ_PASSWORD'] ?? '',
            );
        } catch (Throwable $exception) {
            echo "Whoops!!! Why Me ??? Couldn't connect. Maybe wrong credentials or wrong hostname in .env file ?\n";
            echo "Here's Full Message:\n";
            echo $exception->getMessage() . "\n";
            echo $exception->getTraceAsString() . "\n";
            die();
        }
    }

    /**
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * @return AbstractChannel|\PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel(): \PhpAmqpLib\Channel\AMQPChannel|AbstractChannel
    {
        return $this->channel;
    }

    /**
     * @return array
     */
    protected function declareQueueAndExchange(): array
    {
        $queue = $_ENV['RABBITMQ_QUEUE_NAME'] ?? '';
        $exchange = $_ENV['RABBITMQ_EXCHANGE_NAME'] ?? '';

        $this->getChannel()->queue_declare($queue, false, true, false, false);

        $this->getChannel()->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $this->getChannel()->queue_bind($queue, $exchange);

        return [$queue, $exchange];
    }

    /**
     * @return void
     */
    protected function closeChannelAndConnection(): void
    {
        $this->getChannel()->close();
        try {
            $this->getConnection()->close();
        } catch (Exception $e) {
            // log the problem
        }
    }
}