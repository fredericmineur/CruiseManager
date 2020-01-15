<?php

namespace App\Repository;

use App\Entity\Tripinvestigators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tripinvestigators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tripinvestigators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tripinvestigators[]    findAll()
 * @method Tripinvestigators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripinvestigatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tripinvestigators::class);
    }

    public function findDistinctFirstNames(){
        return $this->createQueryBuilder('tinv')
            ->select('tinv.firstname')
            ->distinct()
            ->orderBy('tinv.firstname')
            ->getQuery()
            ->getResult();
    }

    public function findDistinctSurnames(){
        return $this->createQueryBuilder('tinv')
            ->select('tinv.surname')
            ->distinct()
            ->orderBy('tinv.surname')
            ->getQuery()
            ->getResult();
    }

    public function findForTripAndInvestigator ($tripId, $investigatorId) {
        return $this->createQueryBuilder('tinv')
            ->andWhere('tinv.tripnr = :tripId')
            ->andWhere('tinv.investigatornr = :investigatorId')
            ->setParameter('tripId', $tripId)
            ->setParameter('investigatorId', $investigatorId)
            ->orderBy('tinv.tripnr', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Tripinvestigators[] Returns an array of Tripinvestigators objects
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
    public function findOneBySomeField($value): ?Tripinvestigators
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
