<?php

namespace App\Tests\Repository;

use App\Entity\File;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FileRepositoryTest extends KernelTestCase
{
    private \Doctrine\ORM\EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testFindAll(): void
    {

        $files = $this->entityManager->getRepository(File::class)->findAll();
        $this->assertCount(1, $files);
    }

    public function testAdd(): void
    {

        $fileEntity = new File();

        $fileEntity->setName('depenses_fixtures.csv');
        $fileEntity->setPath('filepath test');
        $fileEntity->setHash('file hash test');
        $fileEntity->setImportDate(new DateTime("2023-11-11"));
        $fileEntity->setMonth("2023-09");
        $fileEntity->setIncome(true);

        $this->entityManager->getRepository(File::class)->add($fileEntity, true);

        $newFileId = $fileEntity->getFileId();

        $files = $this->entityManager->getRepository(File::class)->findAll();

        $this->assertCount(2, $files);
        $this->assertEquals($newFileId, $files[1]->getFileId());
        $this->assertEquals('depenses_fixtures.csv', $files[1]->getName());
        $this->assertEquals('filepath test', $files[1]->getPath());
        $this->assertEquals('file hash test', $files[1]->getHash());
        $this->assertEquals(new DateTime("2023-11-11"), $files[1]->getImportDate());
        $this->assertEquals("2023-09", $files[1]->getMonth());
        $this->assertEquals(true, $files[1]->isIncome());
    }
}
