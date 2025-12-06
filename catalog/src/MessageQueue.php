<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageQueue
{
    private $connection;
    private $channel;

    public function __construct()
    {
        $host = getenv('RABBITMQ_HOST') ?: 'rabbitmq';
        $user = getenv('RABBITMQ_USER') ?: 'user';
        $password = getenv('RABBITMQ_PASS') ?: 'password';

        $this->connection = new AMQPStreamConnection($host, 5672, $user, $password);
        $this->channel = $this->connection->channel();
    }

    public function publish($exchange, $routingKey, $messageBody)
    {
        $this->channel->exchange_declare($exchange, 'topic', false, true, false);

        $msg = new AMQPMessage(
            $messageBody,
            ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish($msg, $exchange, $routingKey);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
