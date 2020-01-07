<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\CruiseType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CruiseController extends AbstractController
{
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


        //Check findBy with first array empty https://stackoverflow.com/questions/7124340/doctrine-coregettable-findall-how-to-specify-order

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
    public function createCruiseOriginal(Request $request, ObjectManager $manager)
    {
        $cruise = new Cruise();
//        $trip1 = new Trip();
//        $trip1->setStartdate(new \DateTime('2020-04-11'))->setEnddate(new \DateTime('2020-04-11'))
//            ->setDestinationarea('BCP');
//        $trip2 = new Trip();
//        $trip2->setStartdate(new \DateTime('2020-04-12'))->setEnddate(new \DateTime('2020-04-12'))
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

//            dd($form);
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
            'firstNamesTripInvJson' => $this->generateJsonDistinctFirstNamesTripInvestigators($manager),
            'surnamesTripInvJson' => $this->generateDistinctSurnamesTripInvestigators($manager)
        ]);
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
            'firstNamesTripInvJson' => $this->generateJsonDistinctFirstNamesTripInvestigators($manager),
            'surnamesTripInvJson' => $this->generateDistinctSurnamesTripInvestigators($manager)
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
//        $campaigns = $cruise->getCampaign();
//        $trips = $this->getDoctrine()->getRepository(Trip::class)
//            ->findBy(['cruiseid'=> $cruiseId], ['startdate'=>'ASC']);

        //Getting start and end date of the cruise (through the trips collection)
//        $startDates = [];
//        $endDates = [];
//        foreach ($trips as $trip){
//            array_push($startDates, $trip->getStartdate());
//            array_push($endDates, $trip->getEnddate());
//        }
//
//        if ($startDates){
//
//        }
//        $cruiseStartDate = !empty($startDates) ?  min($startDates) : null; //if $starDates is not empty
//        $cruiseEndDate = !empty($endDates) ? max($endDates) : null;
//        dump($cruiseStartDate, $cruiseEndDate);

        return $this->render('display/display_cruise.html.twig', [
//            'campaigns' => $campaigns,
            'cruise' => $cruise,
//            'trips' => $trips,
//            'cruiseStartDate' => $cruiseStartDate,
//            'cruiseEndDate' => $cruiseEndDate
        ]);
    }

    public function generateJsonDistinctFirstNamesTripInvestigators (ObjectManager $manager){
        $tripInvestigatorsFirstNames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctFirstNames();
        $arrayFirstNames = [];
        foreach($tripInvestigatorsFirstNames as $firstName) {
            $firstName = trim($firstName['firstname']);
            array_push($arrayFirstNames, $firstName);
        }
        return json_encode($arrayFirstNames);

    }

    public function generateDistinctSurnamesTripInvestigators(ObjectManager $manager) {
        $tripInvestigatorsSurnames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctSurnames();
        $arraySurnames = [];
        foreach ($tripInvestigatorsSurnames as $surname) {
            $surname = trim($surname['surname']);
            array_push($arraySurnames, $surname);
        }
        return json_encode($arraySurnames);
    }




}
