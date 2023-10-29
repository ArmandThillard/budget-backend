<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use DateTime;

use App\Entity\File;

class FileTest extends TestCase
{
    private File $file;

    protected function setUp(): void
    {
        $this->file = new File();
    }
    
    public function testGetName(): void
    {
        $response = $this->file->setName('filename');

        $this->assertInstanceOf(File::class, $response);
        $this->assertEquals('filename', $this->file->getName());
    }
    
    public function testGetPath(): void
    {
        $response = $this->file->setPath('/path/to/file');

        $this->assertInstanceOf(File::class, $response);
        $this->assertEquals('/path/to/file', $this->file->getPath());
    }
    
    public function testGetHash(): void
    {
        $response = $this->file->setHash('qsidgohihnmlkqjozef');

        $this->assertInstanceOf(File::class, $response);
        $this->assertEquals('qsidgohihnmlkqjozef', $this->file->getHash());
    }
    
    public function testGetImportDate(): void
    {
        $response = $this->file->setImportDate(new DateTime('2023-10-25'));

        $this->assertInstanceOf(File::class, $response);
        $this->assertEquals(new DateTime('2023-10-25'), $this->file->getImportDate());
    }
    
    public function testGetMonth(): void
    {
        $response = $this->file->setMonth('2023-01');

        $this->assertInstanceOf(File::class, $response);
        $this->assertEquals('2023-01', $this->file->getMonth());
    }
    
    public function testIsIncome(): void
    {
        $response = $this->file->setIncome(true);

        $this->assertInstanceOf(File::class, $response);
        $this->assertTrue($this->file->isIncome());
    }
}
