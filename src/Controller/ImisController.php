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
use Symfony\Component\Serializer\SerializerInterface;

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


    /**
     * @Route("/imisprojectById/{imisId}", name="findImisProject", options={"expose"=true})
     */
    public static function findImisProject (SerializerInterface $serializer, ImisService $imisService, $imisId)

    {

        //FOR USING IN AUTOCOMPLETE (form campaign)....find a way to wrap the JSON in square brackets?

        $data = $imisService->getProjectByImisId($imisId);

        $response = JsonResponse::fromJsonString($data );
        return $response;



    }

}
