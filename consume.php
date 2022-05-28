<?php

require_once __DIR__ . '/load.php';

use App\MessageConsumer;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

$queue = $_ENV['RABBITMQ_QUEUE_NAME'] ?? '';
$exchange = $_ENV['RABBITMQ_EXCHANGE_NAME'] ?? '';
$consumerTag = 'consumer';

try {
    $connection = new AMQPStreamConnection(
        $_ENV['RABBITMQ_HOST'] ?? '',
        $_ENV['RABBITMQ_PORT'] ?? '',
        $_ENV['RABBITMQ_USERNAME'] ?? '',
        $_ENV['RABBITMQ_PASSWORD'] ?? '',
    );
    $channel = $connection->channel();
}catch (Throwable $exception){
    echo "Whoops!!! Why Me ??? Couldn't connect. Maybe wrong credentials or wrong hostname in .env file ?\n";
    echo "Here's Full Message:\n";
    echo $exception->getMessage() . "\n";
    die();
}

/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$channel->queue_declare($queue, false, true, false, false);

/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

/*
    queue: Queue from where to get the messages
    consumer_tag: Consumer identifier
    no_local: Don't receive messages published by this consumer.
    no_ack: If set to true, automatic acknowledgement mode will be used by this consumer. See https://www.rabbitmq.com/confirms.html for details.
    exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
    nowait:
    callback: A PHP Callback
*/
$channel->basic_consume($queue, $consumerTag, false, false, false, false, [new MessageConsumer(), 'process']);

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param AbstractConnection $connection
 * @throws Exception
 */
function shutdown(\PhpAmqpLib\Channel\AMQPChannel $channel, AbstractConnection $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

// Loop as long as the channel has callbacks registered
while ($channel->is_consuming()) {
    try {
        $channel->wait();
    } catch (ErrorException $e) {
        echo "Whoops! Something went wrong\n";
    }
}