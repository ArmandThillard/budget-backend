<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function update(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return Transaction[] Returns an array of Transaction objects
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.dateOp', 'DESC')
            ->groupBy("t.transactionId")
            ->getQuery()
            ->getResult();
    }
}
