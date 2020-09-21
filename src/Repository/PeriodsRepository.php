<?php

namespace App\Repository;

use App\Entity\Periods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Periods|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periods|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periods[]    findAll()
 * @method Periods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periods::class);
    }

    public function getPeriodsWithColor () {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT id, startdate, enddate, trim(short) as short, trim(colorcode) as colorcode, 
                CASE 
                    WHEN colorcode = \'grey\' THEN \'#F4F4F4\'
                    WHEN colorcode = \'blue\' THEN \'#F0F8FF\'
                    WHEN colorcode = \'red\' THEN \'#FF0000\'
                END AS colorhexcode
            FROM Periods;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $conn->close();
        return $stmt->fetchAll();
    }
}
