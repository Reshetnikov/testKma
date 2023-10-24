<?php
require_once __DIR__ . '/init.php';

$rabbitmqConnection = App::getRabbitmqConnection();
$channel = $rabbitmqConnection->channel();
$queue = $_ENV['RABBITMQ_QUEUE'];

$channel->queue_declare($queue, false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    $url = $msg->getBody();
    $length = strlen(file_get_contents($url));
    echo " [x] Received $url $length \n";

    $mariaDbConnection = App::getMariaDbConnection();
    $sql = "INSERT INTO requests (create_at, length, url) VALUES (?,?,?)";
    $mariaDbConnection->prepare($sql)->execute([date("Y-m-d H:i:s"), $length, $url]);

    $clickHouseConnection = App::getClickHouseConnection();
    $sql = "INSERT INTO requests (id, create_at, length, url) VALUES (generateUUIDv4(), '{create_at}', {length}, '{url}')";
    $clickHouseConnection->write($sql, ['create_at' => date("Y-m-d H:i:s"), 'length' => $length, 'url' => $url]);
};

$channel->basic_consume($queue, '', false, true, false, false, $callback);

try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$channel->close();
$rabbitmqConnection->close();