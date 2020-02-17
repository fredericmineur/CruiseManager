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


    public function getAllTripsForTable ()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT Trip.TripID, TRIM(Trip.destinationarea) as destination, 
        CONVERT(varchar, trip.Startdate, 23) + \'  [\'  
        + CONVERT(varchar, DATEDIFF(day, trip.Startdate, trip.Enddate) +1     ) +\']\'
        as startdateAndNumberOfDays,
        CONVERT(varchar, trip.Enddate, 23) as enddate,
        TRIM(cruise.plancode) as plancode, TRIM(cruise.ship) as ship, TRIM(trip.status) as status, cruise.cruiseID 
        FROM Trip
        LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
        ORDER BY trip.Startdate DESC
         ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTripsForTableWithStation () :array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '

SELECT Trip.TripID, 
            MIN(Trip.DestinationArea) as destination, 
            
            CONVERT(varchar, MIN(trip.Startdate), 103) + \' [\'  
            +  CONVERT(varchar, DATEDIFF(day, MIN(trip.Startdate), MIN(trip.Enddate)) +1     ) +\' ]\'
            as startdateAndNumberOfDays,
            
            CONVERT(varchar, SUM(CASE WHEN (ts.tripNR IS NULL) THEN 0
            ELSE 1 END)) + \' [\' +
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

    public function getTripsWithStations() {
        {
            $qb = $this->createQueryBuilder('trip')
                ->leftJoin('trip.tripstations', 'tripstat')
                ->addSelect('tripstat')
                ->leftJoin('tripstat.stationnr', 'station')
                ->addSelect('station')
            ;
            $query = $qb->getQuery();
            return $query->getArrayResult();

        }
    }

    public function getTripsDiffTripStationStation() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT MAX(Trip.TripID) as TripID, 
            MAX(Trip.CruiseID) as CruiseId,
            MAX(Cruise.Plancode) as plancode,
            CONVERT(varchar, MAX(Trip.Startdate), 23) as StartDate,
            CONVERT(varchar, MAX(Trip.Enddate), 23) as EndDate,
            SUM(CASE WHEN TripStations.ID IS NULL THEN 0 ELSE 1 END) as NbTripStations,
            SUM(CASE WHEN TripStations.StationNR IS NULL THEN 0 ELSE 1 END) as NbStations,
            (SUM(CASE WHEN TripStations.ID IS NULL THEN 0 ELSE 1 END) - SUM(CASE WHEN TripStations.StationNR IS NULL THEN 0 ELSE 1 END)) as NonRegist
            FROM Trip
            LEFT JOIN TRIPSTATIONS ON TripStations.TripNr = Trip.TripID
            FULL OUTER JOIN Stations ON Tripstations.StationNR = Stations.NR
            LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
            GROUP BY Trip.TripID 
            HAVING (SUM(CASE WHEN TRIPSTATIONS.ID IS NULL THEN 0 ELSE 1 END) - SUM(CASE WHEN tripstations.StationNR IS NULL THEN 0 ELSE 1 END)) > 0
            ORDER BY Trip.TripID DESC
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTripsDiffTripInvestigatorsInvestigators() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT MAX(Trip.TripID) as TripID, 
            MAX(Trip.CruiseID) as CruiseId,
            MAX(Cruise.Plancode) as plancode,
            CONVERT(varchar, MAX(Trip.Startdate), 23) as StartDate,
            CONVERT(varchar, MAX(Trip.Enddate), 23) as EndDate,
            SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) as NbTripInvestigators,
            SUM(CASE WHEN TripInvestigators.InvestigatorNR IS NULL THEN 0 ELSE 1 END) as NbInvestigators,
            (SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) - SUM(CASE WHEN TripInvestigators.InvestigatorNR IS NULL THEN 0 ELSE 1 END)) as NonRegist
            FROM Trip
            LEFT JOIN TripInvestigators ON TripInvestigators.TripNr = Trip.TripID
            FULL OUTER JOIN Investigators ON TripInvestigators.InvestigatorNR = Investigators.InvestigatorID
            LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
            GROUP BY Trip.TripID 
            HAVING (SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) - SUM(CASE WHEN TripInvestigators.InvestigatorNR IS NULL THEN 0 ELSE 1 END)) > 0
            ORDER BY Trip.TripID DESC
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getListDestinationArea() {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT DISTINCT TRIM(destinationarea) as destinationlist
            FROM trip WHERE NULLIF (destinationarea, \'\') is not null 
            ORDER BY destinationlist ASC
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function stationTrips ($stationId) {
        return $this->createQueryBuilder('trip')
            ->join('trip.tripstations', 'trip_tripstations')
            ->andWhere('trip_tripstations.stationnr = :stationid')
            ->setParameter('stationid', $stationId)
            ->orderBy('trip.startdate', 'ASC')
            ->getQuery()
            ->execute();
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


/*
 SQL

use [midasmanagement]

GO

SELECT Trip.TripId as TripId,
CONVERT(varchar, DATEDIFF(day, Trip.Startdate, Trip.Enddate)+1) as Days,
CONVERT (varchar, Trip.Startdate, 103)
		+ ' [' + CONVERT(varchar, DATEDIFF(day, Trip.Startdate, Trip.Enddate)+1)
		+ ']'
		+ (CASE
			WHEN (DATEDIFF(day, Trip.Startdate, Trip.Enddate)>1) THEN '*'
			ELSE ''
		  END)
		as StartDayNbDays,

CONVERT (varchar, Trip.Enddate, 103) as EndD,
(CASE WHEN (Trip.DestinationArea IS NULL) THEN '' ELSE Trip.DestinationArea END) as Destination,
Cruise.CruiseID as cruiseID,
Cruise.Plancode as PlanCode,



FROM Trip
LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID

//////////////////////////////////

use [midasmanagement]

GO

SELECT MAX(Trip.TripId) as TripId,
CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1) as Days,
CONVERT (varchar, MAX(Trip.Startdate), 103)
		+ ' [' + CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1)
		+ ']'
		+ (CASE
			WHEN (DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))>1) THEN '*'
			ELSE ''
		  END)
		as StartDayNbDays,

CONVERT (varchar, MAX(Trip.Enddate), 103) as EndD,
(CASE WHEN (MAX(Trip.DestinationArea) IS NULL) THEN '' ELSE MAX(Trip.DestinationArea) END) as Destination,
MAX(Cruise.CruiseID) as cruiseID,
MAX(Cruise.Plancode) as PlanCode,
SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) as TripInvestigatorID



FROM Trip
LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
LEFT JOIN TripInvestigators ON TripInvestigators.TripNr = Trip.TripID
GROUP BY Trip.TripID

////////////////////////////////////////

use [midasmanagement]

GO

SELECT MAX(Trip.TripId) as TripId,
CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1) as Days,
CONVERT (varchar, MAX(Trip.Startdate), 103)
		+ ' [' + CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1)
		+ ']'
		+ (CASE
			WHEN (DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))>1) THEN '*'
			ELSE ''
		  END)
		as StartDayNbDays,

CONVERT (varchar, MAX(Trip.Enddate), 103) as EndD,
(CASE WHEN (MAX(Trip.DestinationArea) IS NULL) THEN '' ELSE MAX(Trip.DestinationArea) END) as Destination,
MAX(Cruise.CruiseID) as cruiseID,
MAX(Cruise.Plancode) as PlanCode,
SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) as NumberTripInvestigators,
SUM(CASE WHEN Investigators.InvestigatorID IS NULL THEN 0 ELSE 1 END) as NumberInvestigators



FROM Trip
LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
LEFT JOIN TripInvestigators ON TripInvestigators.TripNr = Trip.TripID
FULL OUTER JOIN Investigators ON TripInvestigators.InvestigatorNR = Investigators.InvestigatorID
GROUP BY Trip.TripID



////////////////////////////

SELECT MAX(Trip.TripID) as TripID,
SUM(CASE WHEN TRIPSTATIONS.ID IS NULL THEN 0 ELSE 1 END) AS NumberTripstations,
SUM(CASE WHEN TRIPSTATIONS.StationNR IS NULL THEN 0 ELSE 1 END) AS NumberStations



FROM Trip
--LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
LEFT JOIN TRIPSTATIONS ON TRIPSTATIONS.TripNr = Trip.TripID
FULL OUTER JOIN Stations ON TRIPSTATIONS.StationNR = Stations.NR

GROUP BY Trip.TripID
ORDER BY Trip.TripID DESC

////////////////////////// TOTAL QUERY

use [midasmanagement]

GO
SELECT MAX(Trip.TripID) as TripID,
SUM(CASE WHEN TRIPSTATIONS.ID IS NULL THEN 0 ELSE 1 END) AS NumberTripstations,
SUM(CASE WHEN TRIPSTATIONS.StationNR IS NULL THEN 0 ELSE 1 END) AS NumberStations
INTO #temporary_tripstat
FROM Trip
LEFT JOIN TRIPSTATIONS ON TRIPSTATIONS.TripNr = Trip.TripID
FULL OUTER JOIN Stations ON TRIPSTATIONS.StationNR = Stations.NR
GROUP BY Trip.TripID
ORDER BY Trip.TripID DESC;

SELECT MAX(Trip.TripId) as TripId,
CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1) as Days,
CONVERT (varchar, MAX(Trip.Startdate), 103)
		+ ' [' + CONVERT(varchar, DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))+1)
		+ ']'
		+ (CASE
			WHEN (DATEDIFF(day, MAX(Trip.Startdate), MAX(Trip.Enddate))>1) THEN '*'
			ELSE ''
		  END)
		as StartDayNbDays,
CONVERT (varchar, MAX(Trip.Enddate), 103) as EndD,
(CASE WHEN (MAX(Trip.DestinationArea) IS NULL) THEN '' ELSE MAX(Trip.DestinationArea) END) as Destination,
MAX(Cruise.CruiseID) as cruiseID,
MAX(Cruise.Plancode) as PlanCode,
SUM(CASE WHEN TripInvestigators.ID IS NULL THEN 0 ELSE 1 END) as NumberTripInvestigators,
SUM(CASE WHEN Investigators.InvestigatorID IS NULL THEN 0 ELSE 1 END) as NumberInvestigators
INTO #temporary_trip
FROM Trip
LEFT JOIN Cruise ON Cruise.CruiseID = Trip.CruiseID
LEFT JOIN TripInvestigators ON TripInvestigators.TripNr = Trip.TripID
FULL OUTER JOIN Investigators ON TripInvestigators.InvestigatorNR = Investigators.InvestigatorID

GROUP BY Trip.TripID ;

SELECT * FROM #temporary_tripstat JOIN #temporary_trip ON #temporary_tripstat.TripID = #temporary_trip.TripId;

DROP TABLE #temporary_tripstat;
DROP TABLE #temporary_trip;

--HAVING Trip.TripID =3132






 */
