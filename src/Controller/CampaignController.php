<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\CampaignType;
use App\Form\CruiseEditType;
use App\Form\CruiseTrialType;
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






    /*
    /**
     * @Route("/cruises/new", name="cruise_create")
     */
//    public function createCruise(Request $request, ObjectManager $manager){
//        $cruise = new Cruise();
//
//
//
//
//        $form = $this->createForm(CruiseType::class, $cruise);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()){
//            dump($form);
////
//            foreach ($cruise->getTrips() as $trip) {
//                $trip->setCruiseid($cruise);
//                $manager->persist($trip);
//            }
//
//        $manager->persist($cruise);
//        $manager->flush();
//
//        $this->addFlash(
//            'success',
//            "The new cruise <strong>{$cruise->getPlancode()}</strong> has been submitted !"
//        );
//
//
////        return $this->redirectToRoute('cruise_edit', [
////            'cruiseId' => $cruise->getCruiseid()
////        ]);
//
//          return $this->redirectToRoute('cruise_details', [
//              'cruiseId' => $cruise->getCruiseid()
//          ]);
//        }
//        return $this->render('forms/form_cruise_new.html.twig', [
//            'formCruise' => $form->createView(),
////            'mode' => 'create'
//        ]);
//
//
//    }


    /**
     * @Route("/jsonresponse", name="jsonresponse")
     */
    function jsonResponse(ObjectManager $manager){
        $tripInvestigators = $manager->getRepository(Tripinvestigators::class)
            ->findAll();
        $arrayNameInvestigators = [];
        foreach ($tripInvestigators as $tripInvestigator){
            $value = new NameObject();
            $value->first_name = trim($tripInvestigator->getFirstname());
            $value->surname = trim($tripInvestigator->getSurname());
            array_push($arrayNameInvestigators, $value);
        }

        $response = new Response(json_encode($arrayNameInvestigators));
        $response->headers->set('Content-Type', 'application/json');

        return $response;



    }



    /**
     * @Route("/autocomplete_name", name="autocomplete_name")
     */
    public function autocompleteName(){
        return $this->render('trials/autocomplete.html.twig');
    }



    /**
     * @Route("/autocompletetrial", name="autocompletetrial")
     */
    public function autocomplete(ObjectManager $manager){
        $tripInvestigators = $manager->getRepository(Tripinvestigators::class)
            ->findAll();
        $arrayNameInvestigators = [];
        foreach ($tripInvestigators as $tripInvestigator){
            $value = new NameObject();
            $value->first_name = trim($tripInvestigator->getFirstname());
            $value->surname = trim($tripInvestigator->getSurname());
            array_push($arrayNameInvestigators, $value);
        }
        $nameJSON = json_encode($arrayNameInvestigators);
//        dd($nameJSON);


        return $this->render('trials/autocomplete.html.twig', [
            'nameJSON' => $nameJSON

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

class NameObject {

}
