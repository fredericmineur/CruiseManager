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

class CruiseRepository extends ServiceEntityRepository{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cruise::class);
    }

    public function GetAllCruises(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
          SELECT Cruise.[CruiseID]
          , MAX([Plancode]) as plancode
          , MAX(Investigators.Firstname + \'  \'  + Investigators.Surname) as PrincipalInvestigator
          , COUNT(*) as numberOfTrips
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
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function ListCampaignsPerCruise ()
    {
        $qb = $this->createQueryBuilder('cr')
            ->innerJoin('cr.campaign','ca')
            ->addSelect('ca')
        ;
        $query = $qb->getQuery();
        return $query->getArrayResult();
    }

}