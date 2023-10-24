<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class App
{
    protected static $rabbitmqConnection = null;
    protected static $mariaDbConnection = null;
    protected static $clickHouseConnection = null;

    /**
     * @return AMQPStreamConnection|null
     */
    public static function getRabbitmqConnection()
    {
        if (self::$rabbitmqConnection === null) {
            try {
                self::$rabbitmqConnection = new PhpAmqpLib\Connection\AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
            } catch (Throwable $throwable) {
                echo $throwable->getMessage();
            }
        }
        return self::$rabbitmqConnection;
    }

    /**
     * @return PDO|null
     */
    public static function getMariaDbConnection()
    {
        if (self::$mariaDbConnection === null) {
            try {

                self::$mariaDbConnection = new PDO("mysql:dbname={$_ENV['MARIADB_DATABASE']};host=mariadb", $_ENV['MARIADB_USER'], $_ENV['MARIADB_PASSWORD']);
            } catch (Throwable $throwable) {
                echo $throwable->getMessage();
            }
        }
        return self::$mariaDbConnection;
    }

    /**
     * @return PDO|null
     */
    public static function getClickHouseConnection()
    {
        if (self::$clickHouseConnection === null) {
            try {
                $config = [
                    'host' => 'clickhouse',
                    'port' => '8123',
                    'username' => 'default',
                    'password' => ''
                ];
                $db = new ClickHouseDB\Client($config);
                $db->database('default');
                self::$clickHouseConnection = $db;
            } catch (Throwable $throwable) {
                echo $throwable->getMessage();
            }
        }
        return self::$clickHouseConnection;
    }

}