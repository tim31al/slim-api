<?php

namespace Test;

class AppTest extends WebTestCase
{
    use WebTestTrait;

    /**
     * @covers
     */
    public function testApp()
    {
        $response = $this->loadEndpoint();

        $this->assertResponseIsOk($response['info']);

    }

}
