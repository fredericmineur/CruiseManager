<?php
/**
 * Created by PhpStorm.
 * User: filipw
 * Date: 29-01-20
 * Time: 9:38 PM
 */

namespace App\Controller;

use App\Service\ImisService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImisController extends AbstractController
{
    /**
     * @Route("/imispersons/{searchParameter}", name="searchimispersons", options={"expose"=true})
     */
    public function getImisPersons(ImisService $imisService, $searchParameter): JsonResponse
    {
        $data = $imisService->getPersons($searchParameter);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }

    /**
     * @Route("/imisprojects/{searchParameter}", name="searchImisProjects", options={"expose"=true})
     */
    public function getImisProjects(ImisService $imisService, $searchParameter): JsonResponse
    {
        $data = $imisService->getProjects($searchParameter);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }

//    /**
//     * @Route("/imispersonsforfull", name="searchimispersonsforfull", options={"expose"=true})
//     */
//    public function getImisPersonsForFull(ImisService $imisService, $index = 1): JsonResponse
//    {
//        $looping = true;
//        $persons = [];
//        while($looping) {
//            $data = $imisService->getPersonsForFull($index);
//            $persons = array_merge(($persons), json_decode($data, true));
////            $persons = array_merge($persons, $data);
//            $index += 10;
//            if ($index > 50) {
//                $looping = false;
//            }
////            if ($data === NULL || empty($data)){
////                break;
////            }
//        }
//        dump(gettype($persons));
//        dd($persons);
////        $data = $imisService->getPersonsForFull($index);
//        $response = JsonResponse::fromJsonString($persons);
////        dump($data);
//        dd($response);
//        return $response;
//    }

    /**
     * @Route("imisprojectById/{imisId}", name="findImisProject", options={"expose"=true})
     */
    public static function findImisProject (ImisService $imisService, $imisId)

    {

        $data = $imisService->getProjectByImisId($imisId);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }

}
