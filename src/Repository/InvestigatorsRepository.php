<?php

namespace App\Repository;

use App\Entity\Investigators;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

/*
 * sqlSELECT article FROM Entity\Article article WHERE 'string' LIKE CONCAT(article.property, '%');
 * $qb->expr()->concat($qb->expr()->literal(' '), 'p.lastName', )
 *
 * */

    public function findByName ($value){
        return $this->createQueryBuilder('i')
            ->select('CONCAT(CONCAT(i.surname, \' \'), i.firstname) AS fullname,i.firstname, i.surname, i.investigatorid ')
            ->andWhere('CONCAT(CONCAT(i.surname, \' \'), i.firstname) LIKE :query')
            ->setParameter('query', '%' .  $value . '%' )
            ->orderBy('CONCAT(CONCAT(i.surname, \' \'), i.firstname)')
            ->getQuery()
            ->getResult();
    }

    public function giveAllNames (){
        return $this->createQueryBuilder('i')
            ->select('CONCAT(CONCAT(i.surname, \' \'), i.firstname) AS fullname,i.firstname, i.surname, i.investigatorid')
            ->getQuery()
            ->getResult();
    }

}
