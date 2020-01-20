<?php

/**
 * Created by PhpStorm.
 * User: filipw
 * Date: 15-01-20
 * Time: 4:01 PM
 */


namespace App\Repository;

use App\Entity\Cruise;
use App\Entity\Trip;
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
          , SUM(
          CASE
            WHEN (Trip.TripID IS NULL) Then 0
            ELSE 1
           END
          ) as numberOfTrips
          , CONVERT(varchar, MIN(Trip.Startdate), 23) as startdate
          , CONVERT(varchar,MAX(Trip.Enddate) , 23) as enddate
          FROM Cruise 
          LEFT JOIN Trip  ON Cruise.CruiseID =Trip.CruiseID
          LEFT JOIN Investigators  on Investigators.InvestigatorID =Cruise.[PrincipalInvestigator]
          WHERE Plancode is not null  
          GROUP BY Cruise.CruiseID
          ORDER BY Cruise.CruiseID  DESC      
        ';

        $stmt = $conn->prepare($sql);
//        dd($stmt);
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
//        dd($query);
        return $query->getArrayResult();
    }


    public function GetAllCruisesForTable(EntityManagerInterface $em){
//        $query = $em->createQuery(
//            'SELECT cr
//            FROM App\Entity\Cruise cr
//            LEFT JOIN App\Entity\Trip tr ON cr.cruiseid = tr.cruiseid
//            ');
//        dd($query->getResult());

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


//            ->addSelect('ca')
            ->groupBy('cr.cruiseid')
//            ->orderBy('cr.cruiseid', 'DESC')
            ->innerJoin('cr.campaign', 'ca')
//            ->addSelect('ca')

        ;
        $query = $qb->getQuery();
//        dd($query->getArrayResult());
        return $query->getArrayResult();



    }

}
