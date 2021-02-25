<?php

namespace App\Repository;

use App\Entity\TypeTransactionAgence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeTransactionAgence|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTransactionAgence|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTransactionAgence[]    findAll()
 * @method TypeTransactionAgence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeTransactionAgenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeTransactionAgence::class);
    }

    // /**
    //  * @return TypeTransactionAgence[] Returns an array of TypeTransactionAgence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeTransactionAgence
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
