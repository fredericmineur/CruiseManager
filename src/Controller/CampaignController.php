<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\CampaignType;
use App\Form\CruiseEditType;
use App\Form\CruiseType;
use App\Form\TripType;
use App\Repository\CampaignRepository;
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


        $campaign = new Campaign();
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
            'mode' => 'create'
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
            'mode' => 'edit'
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
//        $trip1 = new Trip();
//        $trip1->setStartdate(new \DateTime('2020-04-11'))->setEnddate(new \DateTime('2020-04-11'))
//            ->setDestinationarea('BCP');
//        $trip2 = new Trip();
//        $trip2->setStartdate(new \DateTime('2020-04-11'))->setEnddate(new \DateTime('2020-04-11'))
//            ->setDestinationarea('BCP2');
//        $cruise->addTrip($trip1)->addTrip($trip2);
//        $tripinvestigator1 = new Tripinvestigators();
//        $tripinvestigator2 = new Tripinvestigators();
//        $tripinvestigator3 = new Tripinvestigators();
//        $tripinvestigator1->setFirstname('Fn1')->setSurname('Sn1');
//        $tripinvestigator2->setFirstname('Fn2')->setSurname('Sn2');
//        $tripinvestigator3->setFirstname('Fn3')->setSurname('Sn3');
//        $trip1->addTripinvestigator($tripinvestigator1)->addTripinvestigator($tripinvestigator2)
//            ->addTripinvestigator($tripinvestigator3);







        $form = $this->createForm(CruiseType::class, $cruise);
        $form -> handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($cruise->getTrips() as $trip) {
                $trip->setCruiseid($cruise);

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
        return $this->render('forms/form_cruise_new.html.twig', [
            'formCruise' => $form->createView(),
//            'mode' => 'create'
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
//        dump($originalTrips);


        $form = $this ->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            dump($originalTrips);
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
            'cruise'=> $cruise,
//            'mode' => 'edit'
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
        $trips = $this->getDoctrine()->getRepository(Trip::class)
            ->findBy(['cruiseid'=> $cruiseId], ['startdate'=>'ASC']);

        //Getting start and end date of the cruise (through the trips collection)
        $startDates = [];
        $endDates = [];
        foreach ($trips as $trip){
            array_push($startDates, $trip->getStartdate());
            array_push($endDates, $trip->getEnddate());
        }
        $cruiseStartDate = min($startDates);
        $cruiseEndDate = max($endDates);
        dump($cruiseStartDate, $cruiseEndDate);

        return $this->render('display/display_cruise.html.twig', [
            'campaigns' => $campaigns,
            'cruise' => $cruise,
            'trips' => $trips,
            'cruiseStartDate' => $cruiseStartDate,
            'cruiseEndDate' => $cruiseEndDate
        ]);
    }


    public function cruiseTripEdit (){

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
            return $this->redirectToRoute('cruise_edit', [
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
     * @param CampaignRepository $cr
     */
    public function generateJsonInvestigators(CampaignRepository $cr)
    {
        $array = $cr->arrayCampaigns();

        $arrayCampaignId = [];
        $arrayImis =[];
        $arrayCampaignName = [];
        foreach ($array as $key => $value) {
            array_push($arrayCampaignName, $value["campaign"]);
            array_push($arrayCampaignId, $value["campaignid"]);
            array_push($arrayImis, $value["imisprojectnr"]);
        }

        $arrayCampaigns = ["CampaignImis"=> $arrayImis,
            "CampaignIds"=>$arrayCampaignId, "CampaignNames"=> $arrayCampaignName];
        dd($arrayCampaigns);


//        $arrayJSON = json_encode($array);
//        dump($arrayCampaignId);
//        dump($arrayImis);
//        dump($arrayCampaignName);
//        die;
//        return new JsonResponse($arrayJSON);


//        $allPersonalInvestigators = $this->getDoctrine()
//          ->getRepository(Tripinvestigators::class)->findAll();
//        $arrayJSON = [];
//        foreach ($allPersonalInvestigators as $investigator) {
//            $investigatorName = [];
//
//            $investigatorName['firstname'] = utf8_encode($investigator->getFirstname());
//            $investigatorName['surname'] = utf8_encode($investigator->getSurname());
//            $investigatorNameJSON = json_encode($investigatorName);
//
//            array_push($arrayJSON, $investigatorNameJSON);
//        }
//
//        return new JsonResponse($arrayJSON);

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
