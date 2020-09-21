<?php


namespace App\Controller;


use App\Entity\Periods;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PeriodController extends AbstractController
{
    /**
     * @Route("/api/getperiods", name="get_periods", options={"expose"=true})
     * @return JsonResponse
     */
    public function periodsJsonForTable (SerializerInterface $serializer) {
        $periodRepository = $this->getDoctrine()->getRepository(Periods::class);
        $periods = $periodRepository->findAll();
        $jsonPeriodsForTable = $serializer->serialize($periods, 'json');

        return new JsonResponse($jsonPeriodsForTable, 200, [], true);
    }

    /**
     * @Route("/periods", name="display_periods")
     */
    public function displayPeriods () {
        return $this->render('display/display_periodsList.html.twig');
    }
}