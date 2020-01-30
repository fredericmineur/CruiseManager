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
     * @Route("/imispersons/{searchParameter}", name="searchimispersons")
     */
    public function getImisPersons(ImisService $imisService, $searchParameter): JsonResponse
    {
        $data = $imisService->getPersons($searchParameter);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }

    /**
     * @Route("/imisprojects/{searchParameter}", name="searchImisProjects")
     */
    public function getImisProjects(ImisService $imisService, $searchParameter): JsonResponse
    {
        $data = $imisService->getProjects($searchParameter);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }
}
