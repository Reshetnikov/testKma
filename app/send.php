<?php

require_once __DIR__ . '/init.php';
use PhpAmqpLib\Message\AMQPMessage;

$rabbitmqConnection = App::getRabbitmqConnection();
$channel = $rabbitmqConnection->channel();
$queue = $_ENV['RABBITMQ_QUEUE'];

$channel->queue_declare($queue, false, false, false, false);

$handle = fopen(__DIR__ . '/../urls.txt', "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $url = trim($line);
        if (strlen($url)) {
            $sleep = rand(10,100);
            echo " [x] Sent $url after $sleep seconds\n";
            sleep($sleep);
            $msg = new AMQPMessage($url);
            $channel->basic_publish($msg, '', $queue);
        }
    }
    fclose($handle);
}

$channel->close();
$rabbitmqConnection->close();
?>