<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Stations;
use App\Entity\Trip;
use App\Entity\Tripstations;
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
                        $station->getLongitude(),
                        $station->getLatitude()
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
     * This a lighter version of the station list (it doesn't contain tripstations, coordinates rounded to 4 digits)
     * @Route("/api/getAllStationsTrim", name = "api_get_all_stations_trim", options={"expose"=true})
     */
    public function getAllStationsTrim (SerializerInterface $serializer, StationRepository $stationRepository)
    {

        $stations = $stationRepository->findAllStationsFourDigitsNoTrips();
        $jsonStations = $serializer->serialize($stations, 'json', ['groups'=>'read:all_stations']);
        return new JsonResponse($jsonStations, 200, [], true);
    }


    /**
     * @Route("/stations/new/{lat}-{long}-{code}", name="create_station", defaults={"lat"=null, "long"=null, "code"=null}, options={"expose"=true} )
     */
    public function createStation(Request $request, EntityManagerInterface $manager, $lat, $long, $code, $stationId)
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

    /**
     * @Route("/stations/edit/{stationId}", name="edit_station")
     */
    public function editStation(Request $request, EntityManagerInterface $manager, $stationId){
        $station = $this->getDoctrine()->getRepository(Stations::class)
            ->findOneBy(['nr' => $stationId]);
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
            'mode' => 'edit'
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
     * @Route("/stations/display/{stationId}", name="display_station" , options={"expose"=true})
     */
    public function displayStation (StationRepository $stationRepository, EntityManagerInterface $manager, $stationId)
    {
        $station = $stationRepository->findOneBy(['nr' => $stationId]);
        $trips = $manager->getRepository(Trip::class)->stationTrips($stationId);
        $cruises = $manager->getRepository(Cruise::class)->stationCruise($stationId);
//        dd($cruises);

        return $this->render('display/display_station.html.twig',[
            'station' => $station,
            'trips' => $trips,
            'cruises' => $cruises
        ]);
    }

    /**
     * @Route("/stations/displayAll", name="display_all_stations")
     */
    public function displayAllStations (StationRepository $stationRepository)
    {
        return $this->render('display/display_stations.html.twig');
    }

    /**
     * @Route("/stations/remove_station_warning/{stationId}", name="remove_station_warning")
     */
    public function warningRemoveStation (EntityManagerInterface $manager, $stationId){
        $station = $manager->getRepository(Stations::class)->findOneBy(['nr' => $stationId]);
        return $this->render('remove/remove_station.html.twig', [
            'station' => $station
        ]);
    }

    /**
     * @Route("/stations/remove_station/{stationId}", name="remove_station")
     */
    public function removeStation(EntityManagerInterface $manager, $stationId) {
        $station = $manager->getRepository(Stations::class)
            ->findOneBy(['nr' => $stationId]);
        $manager->remove($station);
        return $this->redirectToRoute('display_all_stations');
    }

    /**
     * @Route("/stations/map", name="stations_map")
     */
    public function stationsMap(){
        return $this->render('display/display_stations_trialMap.html.twig');
    }




}
