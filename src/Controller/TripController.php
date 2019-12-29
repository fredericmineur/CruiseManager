<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{

    /**
     * @Route("/trips/{tripId}", name="trip_details")
     */
    public function tripDetails(ObjectManager $manager, $tripId){
        $trip = $manager->getRepository(Trip::class)->findOneBy(['tripid'=>$tripId]);
        return $this->render();
    }

    /**
     * @Route("/trips/{tripId}/edit", name="trip_edit")
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
}
