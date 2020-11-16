<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
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

        foreach ($trip->getTripinvestigators() as $tripinvestigator) {
            if ($tripinvestigator->getInvestigatornr()!== null) {
                $tripinvestigator->setFullname($tripinvestigator->getInvestigatornr()->getSurname(). ' '  . $tripinvestigator->getInvestigatornr()->getFirstname());
            }
        }

        $form = $this->createForm(TripType::class, $trip);
        $form ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                if ($tripinvestigator->getFullname() === '' || $tripinvestigator->getFullname() === null) {
                    $tripinvestigator->setInvestigatornr(null);
                }
                if($tripinvestigator->getCampaign() === '' || $tripinvestigator->getCampaign() === null ) {
                    $tripinvestigator->setCampaignnr(null)
                    ->setCampaign(null);
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

    /**
     * @Route("/trips/{tripId}/remove_warning", name="trip_remove_warning", options={"expose"=true})
     * @param $tripId
     */
    public function warnRemoveSingleTrip(EntityManagerInterface $manager, $tripId){
        $trip = $manager->getRepository(Trip::class)->findOneBy(['tripid'=> $tripId]);
        return $this->render('remove/remove_trip.html.twig', [
            'trip' => $trip
        ]);
    }

    /**
     * @Route("/trips/{tripId}/remove", name="trip_remove", options={"expose"=true})
     * @param $tripId
     */
    public function removeSingleTrip(EntityManagerInterface $manager, $tripId) {
        $trip = $manager->getRepository(Trip::class)->findOneBy(['tripid'=> $tripId]);
        $manager->remove($trip);
        $manager->flush();
        return $this->redirectToRoute('trips_index');
    }

}