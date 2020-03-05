<?php

namespace App\Repository;

use App\Entity\Stations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Stations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stations[]    findAll()
 * @method Stations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stations::class);
    }

    public function idInTripStation ($stationId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql ='
        SELECT listStationsTripStation FROM Stations 
            LEFT JOIN 
            (SELECT DISTINCT(StationNR) as listStationsTripStation FROM TRIPSTATIONS WHERE StationNr is not null ) AS StationWithTripstations
            ON StationWithTripstations.listStationsTripStation = Stations.nr
            WHERE listStationsTripStation = ?
        ';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $stationId);
        $stmt->execute();
        return $stmt->fetchColumn();

    }

    public function listStationCodes ()
    {
        return $this->createQueryBuilder('s')
            ->select('s.code as stationCode', 's.nr as id',
                's.latitude', 's.longitude')
            ->orderBy('s.code')
            ->getQuery()
            ->getResult();
    }

    public function listStationCodesWithConcat($value)
    {$conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT 
            TRIM(s.code) as stationCode, s.NR as id, s.latitude as Lat, s.Longitude as Long,
            CONCAT(TRIM(s.code),\' (Lat:\', ROUND(s.latitude, 2), \'_\' , \'Long:\',ROUND(s.Longitude,2), \')\') as LL
            FROM Stations as s WHERE s.code LIKE ?
        ';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1,'%'.$value.'%');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllStationsFourDigitsNoTrips () {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ' SELECT NR, ROUND(Latitude, 4) as Lat, ROUND(Longitude, 4) as Long, Code FROM STATIONS
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

    }


    public function findTripforStation () {
        return $this->createQueryBuilder();
    }

}
