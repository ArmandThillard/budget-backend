<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testgetFiles(): void
    {
        $this->client->request('GET', '/file');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful('Response is succesful');
        $this->assertSame($response->headers->get('content-type'), 'application/json');
        $this->assertGreaterThanOrEqual(1, \count(json_decode($response->getContent(), true)), 'Empty response');
    }
}
