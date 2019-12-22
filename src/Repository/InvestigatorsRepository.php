<?php

namespace App\Repository;

use App\Entity\Investigators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Investigators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investigators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investigators[]    findAll()
 * @method Investigators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestigatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investigators::class);
    }


    // /**
    //  * @return Investigators[] Returns an array of Investigators objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Investigators
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
