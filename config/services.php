<?php

use Monolog\Handler\FirePHPHandler;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

return [
    // monolog logger
    Logger::class => function (ContainerInterface $container) {
        $logFile = __DIR__.'/../var/log/' . $container->get('log_file');

        // Create some handlers
        $stream = new StreamHandler($logFile, Logger::DEBUG);
        $firephp = new FirePHPHandler();

        $logger = new Logger('Test');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        return $logger;
    },


    // pdo
    PDO::class => function (ContainerInterface $container) {
        $db = $container->get('db');

        $dsn = sprintf(
            '%s:host=%s;dbname=%s',
            $db['driver'],
            $db['host'],
            $db['name'],
        );

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $db['user'], $db['pass'], $opt);
    }

];
