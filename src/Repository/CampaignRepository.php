<?php

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campaign::class);
    }

    public function generateArrayCampaigns () {

        $campaigns = $this->createQueryBuilder('c')
            ->select('DISTINCT c.campaignid, c.campaign, c.imisprojectnr')
            ->getQuery()
            ->execute();

        $arrayCampaignIds = [];
        $arrayImis =[];
        $arrayCampaignNames = [];
        foreach ($campaigns as $key => $value) {
            array_push($arrayCampaignNames, $value["campaign"]);
            array_push($arrayCampaignIds, $value["campaignid"]);
            array_push($arrayImis, $value["imisprojectnr"]);
        }



        return ["CampaignImis"=> $arrayImis,
            "CampaignIds"=>$arrayCampaignIds, "CampaignNames"=> $arrayCampaignNames];
    }

    public function getCampaignsWithCruises()
    {
        $qb=$this->createQueryBuilder('ca')
            ->innerJoin('ca.cruise', 'cr')
            ->addSelect('cr');
        $query = $qb->getQuery();
        return $query->getArrayResult();
    }


    public function getSlimCampaigns()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ca.campaignid', 'ca.campaign', 'ca.imisprojectnr')
            ->from(Campaign::class, 'ca');
        $query = $qb->getQuery();
        return $query->getArrayResult();
    }






    // /**
    //  * @return Campaign[] Returns an array of Campaign objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Campaign
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
