<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

use App\Entity\Account;

class AccountTest extends TestCase
{
    private Account $account;

    protected function setUp(): void
    {
        $this->account = new Account();
    }

    public function testGetNum(): void
    {
        $response = $this->account->setNum('ACCOUNTNUM');

        $this->assertInstanceOf(Account::class, $response);
        $this->assertEquals('ACCOUNTNUM', $this->account->getNum());
    }
    
    public function testGetLabel(): void
    {
        $response = $this->account->setLabel('ACCOUNTLabel');

        $this->assertInstanceOf(Account::class, $response);
        $this->assertEquals('ACCOUNTLabel', $this->account->getLabel());
    }
}
