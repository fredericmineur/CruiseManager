<?php

namespace App\Controller;

use App\Entity\Stations;
use App\Form\StationType;
use App\Repository\StationRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StationController extends AbstractController
{

    /**
     * @Route("/api/getStations/{value}", name="get_stations", options={"expose"=true})
     */
    public function getStations (SerializerInterface $serializer, StationRepository $stationRepository, $value)
    {
//        $stationCodes = $stationRepository->listStationCodes();
        $stationCodes = $stationRepository->listStationCodesWithConcat($value);
        $jsonStationCodes = $serializer->serialize($stationCodes, 'json');
        return new JsonResponse($jsonStationCodes, 200, [], true);
    }

    /**
     * @Route("/stations/new/{lat}-{long}-{code}", name="create_station", defaults={"lat"=null, "long"=null, "code"=null}, options={"expose"=true} )
     */
    public function createStation(Request $request, EntityManagerInterface $manager, $lat, $long, $code)
    {
        $station = new Stations();
        if($lat && $long && $code) {
            $station->setLatitude($lat)->setLongitude($long)->setCode($code);
        }
        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($station);
            $manager->flush();

            return $this->redirectToRoute('display_station' , [
                'station' => $station
            ]);
        }

        return $this->render('forms/form_station.html.twig', [
            'formStation' => $form->createView(),
            'mode' => 'create'
        ]);
    }

//    /**
//     * @Route("/stations/createWithParams/{lat}-{long}-{code}", name="create_station_params")
//     */
//    public function createStationParams (Request $request, EntityManagerInterface $manager, $lat, $long, $code)
//    {
//        $station = new Stations();
//        $station->setLatitude($lat)->setLongitude($long)->setCode($code);
//        dd($station);
//    }



    /**
     * @Route("/stations/display/{stationId}", name="display_station")
     */
    public function displayStation (StationRepository $stationRepository, $stationId)
    {
        $station = $stationRepository->findOneBy(['nr' => $stationId]);
        return $this->render('display/display_station.html.twig',[
            'station' => $station
        ]);
    }


}
