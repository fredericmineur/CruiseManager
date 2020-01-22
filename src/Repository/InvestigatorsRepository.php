<?php

namespace App\Repository;

use App\Entity\Investigators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Investigators|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investigators|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investigators[]    findAll()
 * @method Investigators[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestigatorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investigators::class);
    }



    public function getAllInvestigatorsForTable(EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb -> select('inv.investigatorid',
            'inv.surname', 'inv.firstname', 'inv.imisnr',
            '(inv.surname + \' \'+ inv.firstname) as fullname',
            '(inv.surname +  \', \'+ inv.firstname) as fullname_comma',
            'inv.passengertype'
            )
            ->from(Investigators::class, 'inv')
            ;

        $query = $qb->getQuery();
        return $query->getArrayResult();

    }



    // /**
    //  * @return Investigators[] Returns an array of Investigators objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Investigators
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
