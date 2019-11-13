<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Form\CampaignType;
use App\Form\CruiseType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CampaignController extends AbstractController
{

    /**
     * @Route("/campaigns", name="campaigns_index")
     */
    public function campaignsIndex()
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaigns = $repoCampaigns->findAll();

        return $this->render('display/display_campaigns_index.html.twig', [
            'campaigns' => $campaigns
        ]);
    }


    /**
     * @Route("/campaign/new", name="campaign_create")
     */
    public function createCampaign(Request $request, ObjectManager $manager)
    {

        $cruise1 = new Cruise();
        $cruise1->setStartdate(new \DateTime('2020-04-11'))
            ->setEnddate(new \DateTime('2020-04-11'));

        dump($cruise1);

        $campaign = new Campaign();
        $campaign->addCruise($cruise1);
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($campaign);
            $manager->flush();
            $this->addFlash(
                'success',
                "the campaign <strong>{$campaign->getCampaign()}</strong> has been successfully submitted"
            );
            return $this->redirectToRoute('campaign_details', [
                'campaignId' => $campaign->getCampaignid()
            ]);
        }

        return $this->render('forms/form_campaign.html.twig', [
            'formCampaign' => $form->createView(),
            'newCampaign' => true
        ]);
    }

    /**
     * @Route("/campaign/edit/{campaignId}", name="campaign_edit")
     */
    public function editCampaign($campaignId)
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaign = $repoCampaigns->findOneBy(['campaignid'=> $campaignId]);
        $form = $this->createForm(CampaignType::class, $campaign);
        return $this->render('forms/form_campaign.html.twig', [
            'form' => $form->createView(),
            'newCampaign' => false
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

        return $this->render('display/display_campaign.html.twig', [
                'campaign' => $campaign,
                'cruises' => $cruises
            ]);
    }



    /**
     * @Route("/cruises", name="cruises_index")
     * @Route("/campaign/{campaignId}/cruises", name="cruises_for_campaign")
     */
    public function cruisesIndex($campaignId = null)
    {
        $cruisesForCampaign=false;
        if ($campaignId) {
            $cruisesForCampaign=true;
        }
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
        $repoInvestigator = $this->getDoctrine()->getRepository(Investigators::class);
        $cruises = $repoCruise->findAll();
        $investigators = $repoInvestigator->findAll();
        $repoCampaign = $this->getDoctrine()->getRepository(Campaign::class);
        $campaigns1 = $repoCampaign->findAll();

        $campaign = $repoCampaign->findOneBy(['campaignid' => $campaignId ]);
//        dump($campaign);
//        dump($campaignId);
//        dump($campaigns1);

        return $this->render('display/display_cruises.html.twig', [
            'cruises' => $cruises,
            'investigators' => $investigators,
            'campaign' => $campaign,
            'cruisesForCampaign' =>$cruisesForCampaign
        ]);
    }

    /**
     * @Route("/cruises/new", name="cruise_create")
     */
    public function createCruise(Request $request, ObjectManager $manager)
    {
        $cruise = new Cruise();
        $trip1 = new Trip();
        $trip1->setStartdate(new \DateTime('2020-08-08 08:00:00'));
        $trip1->setEnddate(new \DateTime('2020-08-08 17:00:00'));
        $trip2 = new Trip();
        $trip2->setStartdate(new \DateTime('2020-08-09 08:00:00'));
        $trip2->setEnddate(new \DateTime('2020-08-09 17:00:00'));


//        $trip1->setDestinationarea('BCP');
//        $trip2->setDestinationarea('BCP2');
        $cruise->addTrip($trip1)->addTrip($trip2);
        $form = $this->createForm(CruiseType::class, $cruise);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $startDate = $form->get('startdate')->getData();
//            $enddate = $form->get('enddate')->getData();
//            dump($startDate, $enddate);
            // NB Here startdate and enddate will be transformed (adding 8h and 17h respectively)
            // See buildForm() in CruiseType.php



            $manager->persist($cruise);
            $manager->flush();
            return $this->redirectToRoute('cruise_details', [
                'cruiseId' => $cruise->getCruiseid()
            ]);
        }
        return $this->render('forms/form_cruise.html.twig', [
            'formCruise' => $form->createView()
        ]);
    }

    /**
     * @Route ("/cruises/{cruiseId}", name="cruise_details")
     */
    public function cruiseDetails($cruiseId)
    {
        $repoCruises = $this->getDoctrine()->getRepository(Cruise::class);
        $cruise = $repoCruises->findOneBy(['cruiseid'=>$cruiseId]);
        $campaigns = $cruise->getCampaign();

        return $this->render('display/display_cruise.html.twig', [
            'campaigns' => $campaigns,
            'cruise' => $cruise
        ]);
    }


    /**
     * @Route("/trips", name="trips_index")
     */
    public function tripsIndex()
    {
        $repoTrips = $this->getDoctrine()->getRepository(Trip::class);
        $trips = $repoTrips->findAll();
        return $this->render('display/display_trips.html.twig', [
            'trips'=> $trips
        ]);
    }
}
