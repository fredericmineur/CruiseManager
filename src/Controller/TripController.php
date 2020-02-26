<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Entity\Tripstations;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TripController extends AbstractController
{

    /**
     * @Route("/trips/{tripId}", name="trip_details", options={"expose"=true})
     */
    public function tripDetails(EntityManagerInterface $manager, $tripId){
        $trip = $manager->getRepository(Trip::class)->findOneBy(['tripid'=>$tripId]);


        return $this->render('display/display_trip.html.twig', [
            'trip' => $trip
        ]);
    }

    /**
     * @Route("/trips/{tripId}/edit", name="trip_edit", options={"expose"=true})
     */
public function editTrip($tripId, Request $request, EntityManagerInterface $manager)
{
        $repoTrips = $this->getDoctrine()->getRepository(Trip::class);
        $trip = $repoTrips->findOneBy(['tripid'=>$tripId]);


        $form = $this->createForm(TripType::class, $trip);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                if($tripinvestigator->getCampaign() === '' || $tripinvestigator->getCampaign() === null ) {
                    $tripinvestigator->setCampaignnr(null);
                }
            }


            $manager->persist($trip);
            $manager->flush();

            return $this->redirectToRoute('trip_details', [
                'tripId' => $trip->getTripid()
            ]);
        }


        return $this->render('forms/form_trip.html.twig', [
            'trip' =>$trip,
            'formTrip' => $form->createView()
        ]);

}




    /**
     * @Route("/trips", name="trips_index")
     */
    public function tripsIndex()
    {

        return $this->render('display/display_tripsListAjax.html.twig');
    }

    /**
     * @Route("/api/gettrips", name="api_get_trips", options={"expose"=true})
     */
    public function getTripsForTable(SerializerInterface $serializer, TripRepository $tripRepository, EntityManagerInterface $em)
    {
        $trips = $tripRepository->getAllTripsForTable();
        $jsonTrips = $serializer->serialize($trips, 'json');

        return new JsonResponse($jsonTrips, 200, [], true);
    }

    /**
     * @Route("/api/gettripsdiffstations", name="api_get_trips_diff_stations", options={"expose"=true})
     */
    public function getTripsDiffStations(SerializerInterface $serializer, TripRepository $tripRepository)
    {
        $trips = $tripRepository->getTripsDiffTripStationStation();
        $jsonTrips = $serializer->serialize($trips, 'json');
        return new JsonResponse($jsonTrips, 200, [], true);
    }

    /**
     * @Route("/api/gettripsdiffinvestigators", name="api_get_trips_diff_investigators", options={"expose"=true})
     */
    public function getTripsDiffInvestigators (SerializerInterface $serializer, TripRepository $tripRepository)
    {
        $trips = $tripRepository->getTripsDiffTripInvestigatorsInvestigators();
        $jsonTrips = $serializer->serialize($trips, 'json');
        return new JsonResponse($jsonTrips, 200, [], true);
    }

    /**
     * @Route("/trips/diffinvestigators", name="list_trips_diffinvestigators")
     */
    public function listTripsDiffinvestigators ()
    {
        //TO DO
    }



    /**
     * @Route("/api/list_trip_destinations", name="list_trip_destinations")
     */
    public function listTripDestinations(SerializerInterface $serializer, TripRepository $tripRepository)
    {
        $destinations= $tripRepository->getListDestinationArea();

        $jsonDestinations = $serializer->serialize($destinations, 'json');
        return new JsonResponse($jsonDestinations, 200, [], true);
    }

}

// NB: logic maybe useful for removing cruise/campaigns entries



////MANY TO MANY WITH A SUPPLEMENTARY FIELD IN THE INTERMEDIARY TABLE
////https://openclassrooms.com/forum/sujet/symfony-manytomany-avec-un-champs-supplementaire
////https://github.com/capdigital/manytomanyattribute
//// NB bad approach as tripinvestigators is the exposed entity
//
///**
// * @Route("/trips/{tripId}/edit", name="trip_edit")
// */
//public function editTrip($tripId, Request $request, EntityManagerInterface $manager)
//{
//    $repoTrips = $this->getDoctrine()->getRepository(Trip::class);
//    $trip = $repoTrips->findOneBy(['tripid'=>$tripId]);
////        dump($this->generateJsonInvestigators());
//
//
//    $originalTripinvestigators = new ArrayCollection();
//
//    foreach ($trip->getTripinvestigators() as $tripinvestigator) {
//        $originalTripinvestigators->add($tripinvestigator);
//    }
//
////        $originalTripstations = new ArrayCollection();
//
//
//    $form= $this->createForm(TripType::class, $trip);
//    $form->handleRequest($request);
//
////        dd($this->container);
//
//    if ($form->isSubmitted() && $form->isValid()) {
////            dd($form->get('stations')->getData());
//        foreach ($form->get('stations')->getData() as $st)
//        {
//            if($st !=null && !$trip->getStations()->contains($st))
//            {
//                $newTripstation = new Tripstations();
//                $newTripstation->setTripnr($trip);
//                $newTripstation->setStationnr($st);
//                $newTripstation->setCode($st->getCode());
//                $newTripstation->setDeflatitude($st->getLatitude());
//                $newTripstation->setDeflongitude($st->getLongitude());
////                    $newTripstation->setServerdate(new \DateTime());
//                $trip->addTripstation($newTripstation);
//            }
//        }
//
//
//        foreach ($trip->getStations() as $st)
//        {
//            if(!$form->get('stations')->getData()->contains($st))
//            {
//                $oldTripstation= $this->getDoctrine()->getRepository(Tripstations::class)->findOneBy(['tripnr'=>$trip, 'stationnr' => $st])   ;
//                $trip->removeTripstation($oldTripstation);
//            }
//        }
//
////            dd($trip->getTripstations());
//
//
//        foreach ($originalTripinvestigators as $tripinvestigator) {
//            if (false === $trip->getTripinvestigators()->contains($tripinvestigator)) {
//                $manager->remove($tripinvestigator);
//            }
//
//        }
//
//        foreach ($trip->getTripinvestigators() as $tripinvestigator)
//        {
//            $tripinvestigator = CruiseController::completeTripInvestigatorFields($manager, $tripinvestigator);
////                dd($tripinvestigator);
//            $manager->persist($tripinvestigator);
//        }
//
////            $cruise = $trip->getCruiseid();
//        $manager->persist($trip);
//        $manager -> flush();
//        return $this->redirectToRoute('trip_details', [
//            'tripId' => $trip->getTripid()
//        ]);
//    }
//
//
//
//    return $this->render('forms/form_trip.html.twig', [
//        'trip' => $trip,
//        'formTrip' => $form->createView(),
////            'listfirstnames' => $this->generateJsonInvestigators()[0]
////            'firstNamesTripInvJson' => $this->container->get('App\Controller\CruiseController')->generateJsonDistinctFirstNamesTripInvestigators($manager),
////            'surnamesTripInvJson' => $this->container->get('App\Controller\CruiseController')->generateDistinctSurnamesTripInvestigators($manager)
//        'firstNamesTripInvJson' => CruiseController::generateJsonDistinctFirstNamesTripInvestigators($manager),
//        'surnamesTripInvJson' => CruiseController::generateDistinctSurnamesTripInvestigators($manager)
//
//
//    ]);
//}
