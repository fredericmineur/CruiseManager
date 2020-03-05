<?php

/**
 * Created by PhpStorm.
 * User: filipw
 * Date: 15-01-20
 * Time: 4:01 PM
 */


namespace App\Repository;

use App\Entity\Cruise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


class CruiseRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cruise::class);
    }


    public function GetAllCruisesWithPIAndNumberOfTrips(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
          SELECT Cruise.CruiseID
          , MAX([Plancode]) as plancode
          , MAX(Investigators.Firstname + \'  \'  + Investigators.Surname) as PrincipalInvestigator
          ,MAX(Investigators.InvestigatorID) as PrincipalInvestigatorID
          , SUM(
          CASE
            WHEN (Trip.TripID IS NULL) Then 0
            ELSE 1
           END
          ) as numberOfTrips
          , CONVERT(varchar, MIN(Trip.Startdate), 23) as startdate
          , CONVERT(varchar, MAX(Trip.Enddate) , 23) as enddate
          FROM Cruise 
          LEFT JOIN Trip  ON Cruise.CruiseID =Trip.CruiseID
          LEFT JOIN Investigators  on Investigators.InvestigatorID =Cruise.[PrincipalInvestigator]
          WHERE Plancode is not null  
          GROUP BY Cruise.CruiseID
          ORDER BY Cruise.CruiseID  DESC      
        ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function GetAllCruisesWithCampaigns ()
    {
        $qb = $this->createQueryBuilder('cr')
            ->innerJoin('cr.campaign','ca')
            ->addSelect('ca')
        ;
        $query = $qb->getQuery();
        return $query->getArrayResult();
    }

    public function GetNewPlancode(){
        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'SELECT left(MAX(plancode),2) + \'-\' + cast(cast(right(MAX(trim(plancode)),3) as integer)+10 as nvarchar(max)) as maxplancode
                FROM Cruise';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function GetAllCruisesForTable(EntityManagerInterface $em){


        $qb = $em->createQueryBuilder();
        $qb -> select( 'cr.cruiseid',
            'COUNT(tr.tripid) as counttrips',
            'MAX(cr.plancode) as plancode',
            'MAX(PI.firstname + \'  \'  + PI.surname) as PrincipalInvestigator',
            'MIN(tr.startdate) as StartDate',
            'MAX(tr.startdate) as EndDate'
            )
            ->from(Cruise::class, 'cr')
            ->leftJoin('cr.trips', 'tr')
            ->leftJoin('cr.principalinvestigator', 'PI')


            ->groupBy('cr.cruiseid')

            ->innerJoin('cr.campaign', 'ca')


        ;
        $query = $qb->getQuery();
        return $query->getArrayResult();



    }


    public function stationCruise($stationId){
        return $this->createQueryBuilder('cruise')
            ->join('cruise.trips', 'cruise_trips')
            ->join('cruise_trips.tripstations', 'trip_tripstations')
            ->andWhere('trip_tripstations.stationnr = :stationid')
            ->setParameter('stationid', $stationId)
            ->orderBy('cruise_trips.startdate', 'ASC')
            ->getQuery()
            ->execute();
    }

}
