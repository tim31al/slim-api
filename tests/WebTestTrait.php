<?php


namespace Test;

trait WebTestTrait
{
    protected string $baseUrl = 'http://slim-api';

    private function request($uri = '', $method = 'GET', $data = null, array $headers = null)
    {
        $url = $this->baseUrl . $uri;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (null !== $data)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if (null !== $headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $body = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return array($body, $info);
    }

    //this allows you to write messages in the test output
    private function printToConsole($statement)
    {
        fwrite(STDOUT, $statement . "\n");
    }


}