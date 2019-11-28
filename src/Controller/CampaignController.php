<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\CampaignType;
use App\Form\CruiseType;
use App\Form\TripType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


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
        $trip1->setDestinationarea('BCP');
        $trip2->setDestinationarea('BCP2');
        $cruise->addTrip($trip1)->addTrip($trip2);


        $form = $this->createForm(CruiseType::class, $cruise);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $startDate = $form->get('startdate')->getData();
//            $enddate = $form->get('enddate')->getData();
//            dump($startDate, $enddate);
            // NB Here startdate and enddate will be transformed (adding 8h and 17h respectively)
            // See buildForm() in CruiseType.php
            foreach ($cruise->getTrips() as $trip) {
                $trip->setCruiseid($cruise);
//                $tripStartTime8H = $trip->getStartdate()->add(new \DateInterval('PT8H'));
//                $trip->setStartdate($tripStartTime8H);
//                    $time->add(new \DateInterval('PT8H'));
                $manager->persist($trip);
            }


            $manager->persist($cruise);
            $manager->flush();

            $this->addFlash(
                'success',
                "The new cruise <strong>{$cruise->getPlancode()}</strong> has been submitted !"
            );


            return $this->redirectToRoute('cruise_details', [
                'cruiseId' => $cruise->getCruiseid()
            ]);
        }
        return $this->render('forms/form_cruise.html.twig', [
            'formCruise' => $form->createView()
        ]);
    }


    public function addTripInvestigatorsToTrip(Trip $trip)
    {
    }

    /**
     * @Route("/cruises/{cruiseId}/edit", name="cruise_edit")
     */
    public function editCruise(Request $request, ObjectManager $manager, $cruiseId)
    {
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
        $cruise = $repoCruise->findOneBy(['cruiseid' => $cruiseId]);

        $originalTrips = new ArrayCollection();
//
        foreach ($cruise->getTrips() as $trip) {
//            $tripCopy = clone($trip);
            $originalTrips->add($trip);
        }
        dump($originalTrips);


        $form = $this ->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($originalTrips);
            foreach ($originalTrips as $trip) {
//                dump($cruise->getTrips()->contains($trip));

                if (false === $cruise->getTrips()->contains($trip)) {
//                    $trip->getCruiseid()->removeElement($trip);
//                    dump("does not exit");
                    $manager->remove($trip);
//                    $trip->setCruiseid(null);
//                    $trip->setCruiseid($trip);
                }
            }

            $manager->persist($cruise);
            $manager->flush();

            $this->addFlash(
                'success',
                "The cruise <strong>{$cruise->getPlancode()}</strong> has been edited !"
            );
            return $this->redirectToRoute('cruise_details', [
                'cruiseId' => $cruise->getCruiseid()
            ]);
        }
        return $this->render('forms/form_cruise_edit.html.twig', [
            'formCruise' => $form->createView(),
            'cruise'=> $cruise
        ]);

//        return $this->render('forms/form_cruise_plugin_collection.html.twig', [
//            'formCruise' => $form->createView(),
//            'cruise'=> $cruise
//        ]);
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
     * @Route("/trips/{tripId}", name="trip_edit")
     */
    public function editTrip($tripId, Request $request, ObjectManager $manager)
    {
        $repoTrips = $this->getDoctrine()->getRepository(Trip::class);
        $trip = $repoTrips->findOneBy(['tripid'=>$tripId]);
//        dump($this->generateJsonInvestigators());


        $originalTripinvestigators = new ArrayCollection();

        foreach ($trip->getTripinvestigators() as $tripinvestigator) {
            $originalTripinvestigators->add($tripinvestigator);
        }


        $form= $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTripinvestigators as $tripinvestigator) {
                if (false === $trip->getTripinvestigators()->contains($tripinvestigator)) {
                    $manager->remove($tripinvestigator);
                }
            }
            $cruise = $trip->getCruiseid();
            $manager->persist($trip);
            $manager -> flush();
            return $this->redirectToRoute('cruise_details', [
                'cruiseId' => $cruise->getCruiseId()
            ]);
        }


        return $this->render('forms/form_trip.html.twig', [
            'trip' => $trip,
            'formTrip' => $form->createView(),
//            'listfirstnames' => $this->generateJsonInvestigators()[0]

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


    /**
     * @Route("/jsontrial", name="jsontrial")
     */
    public function generateJsonInvestigators()
    {

        $allPersonalInvestigators = $this->getDoctrine()
          ->getRepository(Tripinvestigators::class)->findAll();
        $arrayJSON = [];
        foreach ($allPersonalInvestigators as $investigator) {
            $investigatorName = [];

            $investigatorName['firstname'] = utf8_encode($investigator->getFirstname());
            $investigatorName['surname'] = utf8_encode($investigator->getSurname());
            $investigatorNameJSON = json_encode($investigatorName);

            array_push($arrayJSON, $investigatorNameJSON);
        }

        return new JsonResponse($arrayJSON);

        //see also
        // https://stackoverflow.com/questions/28141192/return-a-json-array-from-a-controller-in-symfony/34577422




 //        $allPersonalInvestigators = $this->getDoctrine()
 //            ->getRepository(Tripinvestigators::class)->findAll();
 //        $investigatorsJSON = json_encode(utf8_encode($allPersonalInvestigators));
 //        return new Response($investigatorsJSON);


        /*
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);

        $cruise = $this->getDoctrine()->getRepository(Cruise::class)
        ->findOneBy(['cruiseid'=> 20]);
//        dump(get_class($cruise));
//        dd($cruise INSTANCEOF Cruise);
        $cruiseJson = $serializer->serialize($cruise, 'json', [
            'circular_reference_handler' => function ($object) {
            if ($object INSTANCEOF Cruise) {
                return $object->getCruiseid();
            } elseif ($object INSTANCEOF Investigators) {
                return $object->getInvestigatorid();
            } elseif ($object INSTANCEOF Campaign) {
                return $object->getCampaignid();
            } elseif ($object INSTANCEOF Trip) {
                return $object->getTripid();
            } else {
                return $object->getId();
            }

            }
        ]);
        return new Response($cruiseJson, 200, ['Content-Type' => 'application/json']);
//        return new Response('rien');
        */
    }


}
