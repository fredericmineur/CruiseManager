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
        $data = json_decode($data, true);

        foreach($data as $k =>$val) {
            $data[$k]['id']= (int)$data[$k]['RowNum'];
            $data[$k]['text']= $data[$k]['FullPers'];
            unset($data[$k]['RowNum'], $data[$k]['FullPers']);
        }

        $data=json_encode(array('results'=>$data));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/imisprojects/{searchParameter}", name="searchImisProjects", options={"expose"=true})
     */
    public function getImisProjects(ImisService $imisService, $searchParameter): JsonResponse
    {
        $data = $imisService->getProjects($searchParameter);
        $data = json_decode($data, true);

        foreach($data as $k =>$val) {
            $data[$k]['id']= (int)$data[$k]['RowNum'];
            $data[$k]['text']= $data[$k]['StandardTitle'];
            unset($data[$k]['RowNum'], $data[$k]['StandardTitle']);
        }
        $data=json_encode(array('results'=>$data));
        return JsonResponse::fromJsonString($data);
    }

    /**
     * @Route("/imisprojectById/{imisId}", name="findImisProject", options={"expose"=true})
     */
    public static function findImisProject (SerializerInterface $serializer, ImisService $imisService, $imisId)

    {
        $data = $imisService->getProjectByImisId($imisId);
        $response = JsonResponse::fromJsonString($data );
        return $response;
    }

}
