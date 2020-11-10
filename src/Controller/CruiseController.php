<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Tripactions;
use App\Entity\Tripequipment;
use App\Entity\Tripnotes;
use App\Form\CruiseType;
use App\Repository\CruiseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CruiseController extends AbstractController
{



    /**
     * @Route("/cruises", name="cruises_index")
     */
    public function cruises (){
        return $this->render('display/display_cruisesList.html.twig');
    }


    /**
     * @Route("/api/getcruises", name="get_cruises", options={"expose"=true})
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


//TO REMOVE....CHECK
    /**
     * @Route("/api/getcruisesserializer", name="get_cruises_serializer", methods={"GET"})
     */
    public function cruiseJsonForTableSerializer(SerializerInterface $serializer, CruiseRepository $cruiseRepository, EntityManagerInterface $em)
    {

        $cruises= $cruiseRepository->GetAllCruisesForTable($em);

//        $cruisesForTable= $cruiseRepository->GetAllCruisesWithPIAndNumberOfTrips();
//        $cruisesWithCampaigns = $cruiseRepository->GetAllCruisesWithCampaigns();

        $jsonCruisesForTable = $serializer->serialize($cruises, 'json');

        return new JsonResponse($jsonCruisesForTable, 200, [], true);
    }


    /**
     * @Route("/cruises/remove_warning/{cruiseId}", name="cruise_remove_warning", options={"expose"=true})
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

        foreach ($cruise->getCampaign() as $campaign){
            $cruise->removeCampaign($campaign);
        }


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

        $newplancode = $manager->getRepository(Cruise::class)->GetNewPlancode();

        $form = $this->createForm(CruiseType::class, $cruise);
        $form -> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Get the first campaign to assign it to the trip investigators
            $mainCampaign = null;
            foreach($cruise->getCampaign() as $campaign){
                $mainCampaign = $campaign;
                break;
            }
//            dd($cruise);

            foreach ($cruise->getTrips() as $trip) {
                $trip->setCruiseid($cruise);

                foreach ($trip->getTripinvestigators() as $tripinvestigator) {

                        if ($tripinvestigator->getFullname() === '' || $tripinvestigator->getFullname() === null) {
                            $tripinvestigator->setInvestigatornr(null);
                        }
                        if (trim($tripinvestigator->getCampaign()) === '' || $tripinvestigator->getCampaignnr() === null) {
                            $tripinvestigator->setCampaignnr($mainCampaign->getCampaignid())
                                ->setCampaign($mainCampaign->getCampaign());
                        }
                        $manager->persist($tripinvestigator);

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
            'mode' => 'new',
            'newplancode' =>$newplancode,
            'cruiseRemoveDelFunctionality' => null
        ]);
    }


    /**
     * @Route("/cruises/{cruiseId}/edit", name="cruise_edit", options={"expose"=true})
     */
    public function editCruise(Request $request, EntityManagerInterface $manager, $cruiseId)
    {
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
        $cruise = $repoCruise->findOneBy(['cruiseid' => $cruiseId]);


        $originalTrips = new ArrayCollection();

        //Array (to be converted in JS object for removing the delete functionality in the form
        //Idea: id properties of entities are found in hidden fields in the form
        $cruiseRemoveDelFunctionality =[];

        foreach ($cruise->getTrips() as $trip) {

            $removeDeleteTripFunctionality = false;
            $tripid = $trip->getTripid();

            $tripRemoveDelFunctionality = [];

            //Check conditions for removing the delete trip functionality
            if ($trip->getInsync()== 1 || trim($trip->getStatus() == 'Done') ||
                count($trip->getTripactions())>0 || count($trip->getTripequipments())>0 ||
                count($trip->getTripnotes())>0){
                $removeDeleteTripFunctionality = true;
            }
            array_push($tripRemoveDelFunctionality, [$tripid => $removeDeleteTripFunctionality]);

            //Sub-array for tripinvestigators (remove delete functionality for those linked to tripactions)
            $tripRemoveDelTripinvestigators =[];
            foreach ($trip->getTripinvestigators() as $tripinvestigator){
                $removeDelTripinvestigator = false;
                if (count($tripinvestigator->getTripactions())>0){
                    $removeDelTripinvestigator = true;
                }
                array_push($tripRemoveDelTripinvestigators, [$tripinvestigator->getId()=>$removeDelTripinvestigator] );
            }
            array_push($tripRemoveDelFunctionality, ['tripinvestigators' => $tripRemoveDelTripinvestigators]);

            //Sub-array for tripstations (remove delete functionality for those linked to tripactions)
            $tripRemoveDelTripstations=[];
            foreach ($trip->getTripstations() as $tripstation){
                $removeDelTripstation = false;
                if(count($tripstation->getTripactions())>0){
                    $removeDelTripstation = true;
                }
                array_push($tripRemoveDelTripstations, [$tripstation->getId() => $removeDelTripstation]);
            }
            array_push($tripRemoveDelFunctionality, ['tripstations' => $tripRemoveDelTripstations]);

            array_push($cruiseRemoveDelFunctionality, $tripRemoveDelFunctionality);

            //Setting full name for tripinvestigators already in table 'investigator'
            foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                if ($tripinvestigator->getInvestigatornr()!== null) {
                    $tripinvestigator->setFullname($tripinvestigator->getInvestigatornr()->getSurname(). ' '  . $tripinvestigator->getInvestigatornr()->getFirstname());
                }
            }


            $originalTrips->add($trip);

        }

        //Transforming the array into an object
//        $allTripsRemoveDeleteTripFunctionality = json_decode(json_encode($allTripsRemoveDeleteTripFunctionality), FALSE);
        $cruiseRemoveDelFunctionality = json_decode(json_encode($cruiseRemoveDelFunctionality), FALSE);

        $form = $this ->createForm(CruiseType::class, $cruise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Get the first campaign to assign it to the trip investigators (default)
            $mainCampaign = null;
            foreach($cruise->getCampaign() as $campaign){
                $mainCampaign = $campaign;
                break;
            }

            foreach ($originalTrips as $trip) {
                if (false === $cruise->getTrips()->contains($trip)) {

                    $manager->remove($trip);

                } else {
                    foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                        if ($tripinvestigator->getFullname() === '' || $tripinvestigator->getFullname() === null) {
                            $tripinvestigator->setInvestigatornr(null);
                        }
                        if (trim($tripinvestigator->getCampaign()) === '' || $tripinvestigator->getCampaignnr() === null) {
                            $tripinvestigator->setCampaignnr($mainCampaign->getCampaignid())
                                ->setCampaign($mainCampaign->getCampaign());
                        }
                        $manager->persist($tripinvestigator);

                    }
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

        return $this->render('forms/form_cruise.html.twig', [
            'formCruise' => $form->createView(),
            'mode' => 'edit',
            'cruiseRemoveDelFunctionality' => $cruiseRemoveDelFunctionality
        ]);
    }

    /**
     * @Route ("/cruises/{cruiseId}", options={"expose"=true}, name="cruise_details")
     */
    public function cruiseDetails($cruiseId)
    {
        $repoCruises = $this->getDoctrine()->getRepository(Cruise::class);
        $cruise = $repoCruises->findOneBy(['cruiseid'=>$cruiseId]);






        return $this->render('display/display_cruise.html.twig', [

            'cruise' => $cruise,

        ]);
    }





}
