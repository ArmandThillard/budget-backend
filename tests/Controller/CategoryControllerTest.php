<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testGetCategories(): void
    {
        $this->client->request('GET', '/api/category');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful('Response is succesful');
        $this->assertSame($response->headers->get('content-type'), 'application/json');
        $this->assertGreaterThanOrEqual(1, \count(json_decode($response->getContent(), true)), 'Empty response');
    }
}
