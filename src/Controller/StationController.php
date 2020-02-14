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
     * @Route("/api/getStationsGeoJSON", name="get_stations_GeoJSON", options={"expose"=true})
     */
    public function getAllStationsGeoJson (SerializerInterface $serializer, StationRepository $stationRepository){

        //https://medium.com/@sumit.arora/what-is-geojson-geojson-basics-visualize-geojson-open-geojson-using-qgis-open-geojson-3432039e336d
        //https://stackoverflow.com/questions/31885031/formatting-json-to-geojson-via-php

        $stations  = $stationRepository->findAll();
        $features = array();

        foreach ($stations as $key => $station){
            $features[] = array(
                'type' => 'Feature',
                'properties' => array (
                    'stationId' => $station->getNr(),
                    'code' => $station->getCode()
                ),
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array(
                        $station->getLatitude(),
                        $station->getLongitude()
                    )
                )
            );
        }
        $newArrayStations = array('type' => 'FeatureCollection', 'features' => $features);
        $jsonStations = $serializer->serialize($newArrayStations, 'json');
        return new JsonResponse($jsonStations, 200, [], true);

    }

    /**
     * @Route("api/getAllStations", name="api_get_all_stations", options={"expose"=true})
     */
    public function getAllStations (SerializerInterface $serializer, StationRepository $stationRepository)
    {
        $stations = $stationRepository->findAll();
//        foreach ($stations as $station){
////            dd($station->getTripstations());
//        }
//        dd($stations);
        $jsonStations = $serializer->serialize($stations, 'json', ['groups'=>'read:all_stations']);
        return new JsonResponse($jsonStations, 200, [], true);
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

    /**
     * @Route("/stations/displayAll", name="display_all_stations")
     */
    public function displayAllStations (StationRepository $stationRepository)
    {
        return $this->render('display/display_stations.html.twig');
    }




}
