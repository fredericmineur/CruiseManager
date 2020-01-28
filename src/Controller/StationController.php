<?php

namespace App\Controller;

use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StationController extends AbstractController
{

    /**
     * @Route("/api/getStations/{value}", name="get_stations")
     */
    public function getStations (SerializerInterface $serializer, StationRepository $stationRepository, $value)
    {
//        $stationCodes = $stationRepository->listStationCodes();
        $stationCodes = $stationRepository->listStationCodesWithConcat($value);
        $jsonStationCodes = $serializer->serialize($stationCodes, 'json');
        return new JsonResponse($jsonStationCodes, 200, [], true);
    }

}
