<?php

namespace App\Repository;

use App\Entity\Cruise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Cruise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cruise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cruise[]    findAll()
 * @method Cruise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CruiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cruise::class);
    }

    // /**
    //  * @return Cruise[] Returns an array of Cruise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cruise
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
