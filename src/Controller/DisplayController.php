<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DisplayController extends AbstractController
{
    /**
     * @Route("/cruises", name="cruises_index")
     */
    public function cruiseIndex()
    {
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
        $repoInvestigator = $this->getDoctrine()->getRepository(Investigators::class);
        $cruises = $repoCruise->findAll();
        $investigators = $repoInvestigator->findAll();
        return $this->render('display/display_cruises.html.twig', [
            'cruises' => $cruises,
            'investigators' => $investigators
        ]);
    }

    /**
     * @Route("/campaign/{campaignId}", name="campaign_details")
     */
    public function campaignDetails($campaignId)
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaign = $repoCampaigns->findOneBy(['campaignid'=> $campaignId]);
        $cruises = $campaign->getCruise();

//        $iterator = $cruises->getIterator();
//        $iterator->uasort(function($a, $b) {
//            return ($a->getStartdate() < $b->getStartdate()) ? -1 : 1;
//        });
//        $collectionCruises = new ArrayCollection(iterator_to_array($iterator));
//        dump($collectionCruises);

//        dump($cruises);

        return $this->render('display/display_campaign.html.twig',[
                'campaign' => $campaign,
                'cruises' => $cruises
            ]);

    }

}
