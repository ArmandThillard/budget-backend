<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

use App\Entity\Supplier;

class SupplierTest extends TestCase
{
    private Supplier $supplier;

    protected function setUp(): void
    {
        $this->supplier = new Supplier();
    }

    public function testGetName(): void
    {
        $response = $this->supplier->setName('SUPPLIERNAME');

        $this->assertInstanceOf(Supplier::class, $response);
        $this->assertEquals('SUPPLIERNAME', $this->supplier->getName());
    }

    public function testGetLabel(): void
    {
        $response = $this->supplier->setLabel('SUPPLIERLabel');

        $this->assertInstanceOf(Supplier::class, $response);
        $this->assertEquals('SUPPLIERLabel', $this->supplier->getLabel());
    }
}
