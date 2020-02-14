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
    {
        $conn = $this->getEntityManager()->getConnection();
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



    // /**
    //  * @return Stations[] Returns an array of Stations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stations
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
