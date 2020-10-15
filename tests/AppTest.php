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
        list($body, $info) = $this->request();

        $this->assertResponseIsOk($info);

    }

}
