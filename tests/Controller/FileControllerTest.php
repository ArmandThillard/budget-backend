<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FileControllerTest extends WebTestCase
{
    private $client;
    private $apiEndpoint = '/api/file';

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
    }

    public function testGetFiles(): void
    {
        $this->client->request('GET', $this->apiEndpoint);
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful('Response is succesful');
        $this->assertSame($response->headers->get('content-type'), 'application/json');
        $this->assertGreaterThanOrEqual(1, \count(json_decode($response->getContent(), true)), 'Empty response');
    }

    public function testUploadFile(): void
    {
        $body = [
            'filename' => 'depenses_fixtures.csv',
            'importDate' => "2023-11-11",
            'month' => "2023-09",
            'income' => true,
            'data' => file_get_contents(__DIR__ . '/../fixtures/data.csv')
        ];

        $this->client->request(
            'POST',
            $this->apiEndpoint,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($body)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);

        $this->client->request('GET', $this->apiEndpoint);
        $response = $this->client->getResponse();
        $this->assertEquals(2, \count(json_decode($response->getContent(), true)), 'Response contains no file');
    }
}
