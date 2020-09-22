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
            'allTripsRemoveDeleteTripFunctionality' => null
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

        //Idea: link to tripid. In twig file, relate to hidden file
        $allTripsRemoveDeleteTripFunctionality = [];

        foreach ($cruise->getTrips() as $trip) {

            $removeDeleteTripFunctionality = false;
            $tripid = $trip->getTripid();

//Check for the conditions for the possibility of deleting a trip or not
            if ($trip->getInsync()== 1){
                $removeDeleteTripFunctionality = true;
            } elseif ($trip->getStatus() == 'planned') {
                $removeDeleteTripFunctionality = true;
            } else {
                $tripTripactions = $manager->getRepository(Tripactions::class)->findBy(['tripnr' => $tripid], []);
                if(count($tripTripactions)>0) {
                    $removeDeleteTripFunctionality = true;
                } else {
                    $tripTripequipments = $manager->getRepository(Tripequipment::class)->findBy(['tripnr' => $tripid], []);
                    if(count($tripTripequipments)>0){
                        $removeDeleteTripFunctionality = true;
                    } else {
                        $tripTripnotes = $manager->getRepository(Tripnotes::class)->findBy(['tripnr' => $tripid],[]);
                        if(count($tripTripnotes)>0){
                            $removeDeleteTripFunctionality = true;
                        }
                    }
                }
            }



            $tripRemoveDeleteTripFunctionality = [$tripid => $removeDeleteTripFunctionality];


            foreach ($trip->getTripinvestigators() as $tripinvestigator) {
                if ($tripinvestigator->getInvestigatornr()!== null) {
                    $tripinvestigator->setFullname($tripinvestigator->getInvestigatornr()->getSurname(). ' '  . $tripinvestigator->getInvestigatornr()->getFirstname());
                }
            }


            $originalTrips->add($trip);
            array_push($allTripsRemoveDeleteTripFunctionality, $tripRemoveDeleteTripFunctionality);
        }

        //Transforming the array into an object
        $allTripsRemoveDeleteTripFunctionality = json_decode(json_encode($allTripsRemoveDeleteTripFunctionality), FALSE);


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
            'allTripsRemoveDeleteTripFunctionality' => $allTripsRemoveDeleteTripFunctionality

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
