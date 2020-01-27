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
     * @Route("/api/getStations", name="get_stations", methods={"GET"})
     */
    public function getStations (SerializerInterface $serializer, StationRepository $stationRepository)
    {
        $stationCodes = $stationRepository->listStationCodes();
        $jsonStationCodes = $serializer->serialize($stationCodes, 'json');
        return new JsonResponse($jsonStationCodes, 200, [], true);
    }

}
