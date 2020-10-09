<?php


namespace Test;

trait WebTestTrait
{
    protected string $baseUrl = 'http://slim-api';

    private function loadEndpoint($url = '')
    {
        $url = $this->baseUrl . $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return array(
            'body' => $output,
            'info' => $info
        );
    }

    //this allows you to write messages in the test output
    private function printToConsole($statement)
    {
        fwrite(STDOUT, $statement . "\n");
    }


}