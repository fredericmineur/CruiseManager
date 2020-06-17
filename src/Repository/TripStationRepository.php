<?php

namespace App\Repository;

use App\Entity\Tripstations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Tripstations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tripstations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tripstations[]    findAll()
 * @method Tripstations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripStationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tripstations::class);
    }


    public function findStationCodeFromStationTable ($stationId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT CODE from Stations where NR = ?
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $stationId);
        $stmt->execute();
        return $stmt->fetch();
    }

    // /**
    //  * @return Tripstations[] Returns an array of Tripstations objects
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
    public function findOneBySomeField($value): ?Tripstations
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
