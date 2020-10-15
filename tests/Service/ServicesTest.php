<?php

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Monolog\Logger;

class ServicesTest extends TestCase
{
    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = require __DIR__ . '/../../config/bootstrap.php';
    }

    /**
     * @covers
     */
    public function testContainer()
    {
        $appName = $this->container->get('app_name');
        $this->assertSame('Slim-Api', $appName);
    }

    /**
     * @covers
     */
    public function testLogger()
    {
        $log = $this->container->get(Logger::class);
        $this->assertInstanceOf(Logger::class, $log);

        $logFile =  __DIR__ . '/../../var/log/' . $this->container->get('log_file');

        $msg_info = 'info test';

        $log->info($msg_info);

        $content = file_get_contents($logFile);

        $this->assertStringContainsString($msg_info, $content);
    }

    /**
     * @covers
     */
    public function testPdo()
    {
        $dbh = $this->container->get('dbh');
        $this->assertInstanceOf(\PDO::class, $dbh);
    }


}
