<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function getAllTripsForTable () :array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT Trip.TripID, 
            MIN(Trip.DestinationArea) as destination, 
            
            CONVERT(varchar, MIN(trip.Startdate), 103) + \' [ \'  
            +  CONVERT(varchar, DATEDIFF(day, MIN(trip.Startdate), MIN(trip.Enddate)) +1     ) +\' ]\'
            as startdateAndNumberOfDays,
            
            CONVERT(varchar, SUM(CASE WHEN (ts.tripNR IS NULL) THEN 0
            ELSE 1 END)) + \' [ \' +
            CONVERT(varchar, SUM(CASE WHEN (ts.StationNR IS NULL) THEN 0
            ELSE 1 END)) + \' ] \' as stationTotalMidas,
            CONVERT(varchar, MIN(trip.Enddate), 23) as enddate,
            MIN(cruise.Plancode) as plandcode,
            MIN(trip.Status) as status,
            MIN(trip.ship) as ship,
            MIN(cruise.CruiseID) as CruiseID
        FROM Trip   
        LEFT JOIN TRIPSTATIONS as ts ON trip.TripID = ts.TripNr
        LEFT JOIN Cruise  ON cruise.CruiseID = trip.CruiseID
            GROUP BY trip.TripID
            ORDER BY Trip.TripID DESC

        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
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
    public function findOneBySomeField($value): ?Trip
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
