<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sqlCategory = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'categoryFixtures.sql');
        $sqlAccount = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'accountFixtures.sql');
        $sqlSupplier = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'supplierFixtures.sql');
        $sqlFile = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fileFixtures.sql');
        $sqlTransaction = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'transactionFixtures.sql');

        /* @var EntityManagerInterface $manager */
        $connection = $manager->getConnection();
        $connection->executeQuery($sqlCategory);
        $connection->executeQuery($sqlAccount);
        $connection->executeQuery($sqlSupplier);
        $connection->executeQuery($sqlFile);
        $connection->executeQuery($sqlTransaction);

        $manager->flush();
    }
}
