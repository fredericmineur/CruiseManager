<?php

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
            $arrayCampaignNames[]= $value["campaign"];
            $arrayCampaignIds[]= $value["campaignid"];
            $arrayImis[]= $value["imisprojectnr"];
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

    public function searchCampaignName ($value) {
        return $this->createQueryBuilder('c')
            ->select('c.campaignid as campaignid', 'c.campaign as name', 'c.imisprojectnr as imis')
            ->andWhere('c.campaign LIKE :query')
            ->setParameter('query', '%'.$value.'%')
            ->orderBy('c.campaign', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchCampaignImis ($value) {
        return $this->createQueryBuilder('c')
            ->select('c.campaignid as campaignid', 'c.campaign as name', 'c.imisprojectnr as imis')
            ->andWhere('c.imisprojectnr = :query')
            ->setParameter('query', $value)
            ->getQuery()
            ->getResult();
    }

}
