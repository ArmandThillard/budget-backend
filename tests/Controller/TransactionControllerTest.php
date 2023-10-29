<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityNotFoundException;
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
        $this->client->request('GET', '/api/transaction');
        $response = $this->client->getResponse();

        $this->assertResponseIsSuccessful('Response is successfull');
        $this->assertSame($response->headers->get('content-type'), 'application/json');
        $this->assertGreaterThanOrEqual(1, \count(json_decode($response->getContent(), true)), 'Empty response');
    }

    public function testPutTransaction(): void
    {
        $data = [
            'transactionId' => 0,
            'dateOp' => "2022-09-06 00:00:00.000000",
            'dateVal' => "2022-09-06 00:00:00.000000",
            'label' => 'transaction label test',
            'categoryId' => 0,
            'supplierId' => 0,
            'amount' => 15.32,
            'accountId' => 'transaction comment test',
            'pointed' => true,
            'need' => false,
            'fileId' => 0
        ];

        $this->client->request('PUT', '/api/transaction/0', [], [], [], json_encode($data));

        $this->assertResponseIsSuccessful('Response is succesfull');
    }

    public function testPutTransactionOnFailure(): void
    {
        $this->client->catchExceptions(false);

        $this->expectException(EntityNotFoundException::class);

        $data = [
            'transactionId' => 0,
            'dateOp' => "2022-09-06 00:00:00.000000",
            'dateVal' => "2022-09-06 00:00:00.000000",
            'label' => 'transaction label test',
            'categoryId' => 0,
            'supplierId' => 0,
            'amount' => 15.32,
            'accountId' => 'transaction comment test',
            'pointed' => true,
            'need' => false,
            'fileId' => 0
        ];

        $this->client->request('PUT', '/api/transaction/1500', [
            'body' => json_encode($data)
        ]);

        $this->client->catchExceptions(true);
    }
}
