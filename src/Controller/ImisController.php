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
     * @Route("/imispersons/{searchparameter}", name="searchimispersons")
     */
    public function index(ImisService $imisService, $searchparameter): JsonResponse
    {
        $data = $imisService->getPersons($searchparameter);
        $response = JsonResponse::fromJsonString($data);
        return $response;
    }
}
