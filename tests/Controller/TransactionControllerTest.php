<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testGetTransactionsSuccessful(): void
    {
        $this->client->request('GET', '/transaction');
        $response = $this->client->getResponse();

        // print_r($response);

        $this->assertResponseIsSuccessful('Response is successfull');
        // $this->assertSame($response->headers->get('content-type'), 'application/json');
        $this->assertGreaterThanOrEqual(2, \count(json_decode($response->getContent(), true)), 'Empty response');
    }
}
