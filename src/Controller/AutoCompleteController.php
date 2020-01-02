<?php

namespace App\Controller;

use App\Entity\Tripinvestigators;
use App\Repository\CampaignRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoCompleteController extends AbstractController
{
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
     * @Route("/autocomplete2", name="autocomplete2")
     */
    public function autocompleteFirstNames(ObjectManager $manager){
        $tripinvestigatorsFirstNames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctFirstNames();
        $tripinvestigatorsSurnames = $manager->getRepository(Tripinvestigators::class)
            ->findDistinctSurnames();
        $arrayFirstNameTripInvestigators = [];
        $arraySurnameTripInvestigators = [];
        foreach ($tripinvestigatorsFirstNames as $firstName) {
            $firstName = trim($firstName['firstname']);
//            dd($firstName);
            array_push($arrayFirstNameTripInvestigators,$firstName);
        }
        foreach ($tripinvestigatorsSurnames as $surname){
            $surname = trim($surname['surname']);
            array_push($arraySurnameTripInvestigators, $surname);
        }
        $firstNamesJSON = json_encode($arrayFirstNameTripInvestigators);
        $surnamesJSON = json_encode($arraySurnameTripInvestigators);
//        dd($surnamesJSON);
//        dd($firstNamesJSON);
        return $this->render('trials/autocomplete.html.twig',[
            'firstNamesJSON' => $firstNamesJSON,
            'surnamesJSON' => $surnamesJSON
        ]);

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
        dd($nameJSON);




//        return $this->render('trials/autocomplete.html.twig', [
//            'nameJSON' => $nameJSON

//        ]);
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
