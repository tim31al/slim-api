<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class WebTestCase extends TestCase
{
    /**
     * @param array $response
     */
    public function assertResponseIsOk(array $response): void
    {
        static::assertSame(200, $response['http_code']);
//        static::assertStringContainsString('200 OK', $response);
    }

}