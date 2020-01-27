<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Entity\Tripstations;
use App\Form\CruiseType;
use App\Repository\CruiseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CruiseController extends AbstractController
{
//
//  /**
//  * @Route("/campaign/{campaignId}/cruises", name="cruises_for_campaign")
//  */
//
//    public function cruisesIndex($campaignId = null)
//
//    {
////        $cruisesForCampaign=false;
////        if ($campaignId) {
////            $cruisesForCampaign=true;
////        }
//        $cruises = $manager->getRepository(Cruise::class)->findBy([], ['plancode'=> 'DESC']);
////        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
////        $repoInvestigator = $this->getDoctrine()->getRepository(Investigators::class);
//
//
//        //Check findBy with first array empty https://stackoverflow.com/questions/7124340/doctrine-coregettable-findall-how-to-specify-order
//
////        $cruises = $repoCruise->findAll();
////        $investigators = $repoInvestigator->findAll();
////        $repoCampaign = $this->getDoctrine()->getRepository(Campaign::class);
////        $campaigns1 = $repoCampaign->findAll();
//
////        $campaign = $repoCampaign->findOneBy(['campaignid' => $campaignId ]);
//
//        return $this->render('display/display_cruises.html.twig', [
//            'cruises' => $cruises,
////            'investigators' => $investigators,
////            'campaign' => $campaign,
////            'cruisesForCampaign' =>$cruisesForCampaign
//        ]);
//    }


    /**
     * @Route("/cruises", name="cruises_index")
     */
    public function cruises (){
        return $this->render('display/display_cruisesList.html.twig');
    }


//Postman on localhost: 7.04s, 42.36s, 18.56s, 6.98s, 32.06s
    /**
     * @Route("/api/getcruises", name="get_cruises")
     * @return JsonResponse
     */

    public function cruiseJsonForTable()
    {
        $cruises=$this->getDoctrine()
            ->getRepository(Cruise::class)->GetAllCruisesWithPIAndNumberOfTrips();

        $campaigns=$this->getDoctrine()
            ->getRepository(Cruise::class)->GetAllCruisesWithCampaigns();

        foreach ($campaigns as $campaign) {
           $cruisekey = array_search($campaign['cruiseid'], array_column($cruises, 'CruiseID'));
           foreach ($campaign['campaign'] as $k=>$v) {
               unset($campaign['campaign'][$k]['memo']);
               unset($campaign['campaign'][$k]['imisprojectnr']);
           }
           $cruises[$cruisekey]['campaigns']= $campaign['campaign'];
       }

        return  new JsonResponse(array('data'=>$cruises));
    }

    /**
     * @Route("/api/getcruisesserializer", name="get_cruises_serializer", methods={"GET"})
     */
    public function cruiseJsonForTableSerializer(SerializerInterface $serializer, CruiseRepository $cruiseRepository, EntityManagerInterface $em)
    {

        $cruises= $cruiseRepository->GetAllCruisesForTable($em);

        $cruisesForTable= $cruiseRepository->GetAllCruisesWithPIAndNumberOfTrips();
        $cruisesWithCampaigns = $cruiseRepository->GetAllCruisesWithCampaigns();
//        dd($cruisesForTable);
        $jsonCruisesForTable = $serializer->serialize($cruises, 'json');

        return new JsonResponse($jsonCruisesForTable, 200, [], true);
    }


    /**
     * @Route("/cruises/remove_warning/{cruiseId}", name="cruise_remove_warning")
     */
    public function warnRemoveCruise(EntityManagerInterface $manager, $cruiseId)
    {
        $cruise = $manager->getRepository(Cruise::class)->findOneBy(['cruiseid' => $cruiseId]);
        return $this->render('remove/remove_cruise.html.twig', [
            'cruise' => $cruise
        ]);
    }

    /**
     * @Route("/cruises/remove_cruise/{cruiseId}", name="cruise_remove")
     */
    public function removeCruise(EntityManagerInterface $manager, $cruiseId)
    {
        $cruise = $manager->getRepository(Cruise::class)->findOneBy(['cruiseid'=>$cruiseId]);
//        foreach ($cruise->getTrips() as $trip) {
//            foreach ($trip->getTripinvestigators() as $tripinvestigator)
//            {
//                $trip->removeTripinvestigator($tripinvestigator);
//            }
//            $manager->persist($trip);
//        }
        $manager->remove($cruise);
        $manager->flush();
        return $this->redirectToRoute('cruises_index');
    }


    /**
     * @Route("/cruises/new", name="cruise_create")
     */
    public function createCruiseOriginal(Request $request, EntityManagerInterface $manager)
    {
        $cruise = new Cruise();
        $trip1 = new Trip();
        $trip1->setStartdate(new \DateTime('2020-04-11'))->setEnddate(new \DateTime('2020-04-11'))
            ->setDestinationarea('BCP');
        $trip2 = new Trip();
        $trip2->setStartdate(new \DateTime('2020-04-12'))->setEnddate(new \DateTime('2020-04-12'))
            ->setDestinationarea('BCP2');
        $cruise->addTrip($trip1)->addTrip($trip2);
//        $tripinvestigator1 = new Tripinvestigators();
//        $tripinvestigator2 = new Tripinvestigators();
//        $tripinvestigator3 = new Tripinvestigators();
//        $tripinvestigator1->setFirstname('Fn1')->setSurname('Sn1');
//        $tripinvestigator2->setFirstname('Fn2')->setSurname('Sn2');
//        $tripinvestigator3->setFirstname('Fn3')->setSurname('Sn3');
//        $trip1->addTripinvestigator($tripinvestigator1)->addTripinvestigator($tripinvestigator2)
//            ->addTripinvestigator($tripinvestigator3);
        $tripStation1 = new Tripstations();
        $tripStation2 = new Tripstations();
        $tripStation3 = new Tripstations();
        $tripStation1->setCode('test1');
        $tripStation2->setCode('test2');
        $tripStation3->setCode('test3');
        $trip1->addTripstation($tripStation1)->addTripstation($tripStation2);
        $trip2->addTripstation($tripStation3);




        $form = $this->createForm(CruiseType::class, $cruise);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($cruise->getTrips() as $trip) {
                $trip->setCruiseid($cruise);

                foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                    if ($tripinvestigator->getFullname() === '' || $tripinvestigator->getFullname() === null) {
                        $tripinvestigator->setInvestigatornr(null);
                        //$tripinvestigator = self::completeTripInvestigatorFields($manager, $tripinvestigator);
                        $manager->persist($tripinvestigator);
                    }
                }

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
            'formCruise' => $form->createView(),
            'mode' => 'new'
        ]);
    }


    /**
     * @Route("/cruises/{cruiseId}/edit", name="cruise_edit")
     */
    public function editCruise(Request $request, EntityManagerInterface $manager, $cruiseId)
    {
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
        $cruise = $repoCruise->findOneBy(['cruiseid' => $cruiseId]);

        //Due to the presence of some '0' values for the principal investigator ID in the database
        if($cruise->getPrincipalinvestigator()->getInvestigatorId()==0){
            $cruise->setPrincipalinvestigator(null);
        }

        $originalTrips = new ArrayCollection();

        foreach ($cruise->getTrips() as $trip) {
            foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                if ($tripinvestigator->getInvestigatornr()!== null) {
                    $tripinvestigator->setFullname($tripinvestigator->getInvestigatornr()->getSurname(). ' '  . $tripinvestigator->getInvestigatornr()->getFirstname());
                }
            }
            $originalTrips->add($trip);
        }


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
                } else {
                    foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                        if ($tripinvestigator->getFullname() === '' || $tripinvestigator->getFullname() === null) {
                            $tripinvestigator->setInvestigatornr(null);
                            //$tripinvestigator = self::completeTripInvestigatorFields($manager, $tripinvestigator);
                            $manager->persist($tripinvestigator);
                        }
                    }
                }

                /*
                foreach ($trip->getTripinvestigators() as $tripinvestigator)
                {
                    $tripinvestigator = self::completeTripInvestigatorFields($manager, $tripinvestigator);
                    $manager->persist($tripinvestigator);
                }
                 */
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
        return $this->render('forms/form_cruise.html.twig', [
            'formCruise' => $form->createView(),
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route ("/cruises/{cruiseId}", options={"expose"=true}, name="cruise_details")
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

    public static function generateJsonDistinctFirstNamesTripInvestigators (EntityManagerInterface $manager){
        $tripInvestigatorsFirstNames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctFirstNames();
        $arrayFirstNames = [];
        foreach($tripInvestigatorsFirstNames as $firstName) {
            $firstName = trim($firstName['firstname']);
            array_push($arrayFirstNames, $firstName);
        }
        return json_encode($arrayFirstNames);

    }

    public static function generateDistinctSurnamesTripInvestigators(EntityManagerInterface $manager) {
        $tripInvestigatorsSurnames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctSurnames();
        $arraySurnames = [];
        foreach ($tripInvestigatorsSurnames as $surname) {
            $surname = trim($surname['surname']);
            array_push($arraySurnames, $surname);
        }
        return json_encode($arraySurnames);
    }

    /**
     * If the tripinvestigator is in the investigators table, fill the required file
     */
    public static function completeTripInvestigatorFields(EntityManagerInterface $manager, Tripinvestigators $tripinvestigator) :Tripinvestigators
    {
        $investigators = $manager->getRepository(Investigators::class)
            ->findAll();
        foreach ($investigators as $investigator)
        {
            if (($tripinvestigator->getFirstname() != null)
                && ($tripinvestigator->getFirstname() == $investigator->getFirstname())
                && ($tripinvestigator->getSurname() != null)
                && ($tripinvestigator->getSurname() == $investigator->getSurname()))
            {
                $tripinvestigator->setInvestigatornr($investigator)
                    ->setImisnr($investigator->getImisnr())
                    ->setPassengertype($investigator->getPassengertype())
//                    ->setBirthdate($investigator->getBirthdate())
                    ->setNationality($investigator->getNationality());
                return $tripinvestigator;
            }
        }
        $tripinvestigator-> setInvestigatornr(null)
            ->setImisnr(null)
            ->setPassengertype(null)
//            ->setBirthdate(null)
            ->setNationality(null);
        return $tripinvestigator;
    }

}
