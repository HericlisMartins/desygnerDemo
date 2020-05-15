<?php

namespace App\Tests;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    public function testReadLibrary()
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            'http://localhost:8000/api/image/readLibrary'
        );

        $this->assertEquals(200, $response->getStatusCode());
        $headers = $response->getHeaders();
        $this->assertEquals("application/json", $headers["content-type"][0]);
    }
}
