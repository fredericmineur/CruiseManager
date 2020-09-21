<?php


namespace App\Controller;


use App\Entity\Periods;
use App\Form\PeriodType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/periods/new", name="create_period")
     */
    public function createPeriod(EntityManagerInterface $manager, Request $request) {
        $period = new Periods();
        $form = $this->createForm(PeriodType::class, $period);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            switch ($period->getShort()){
                case 'Multi-day trip': $period->setColorcode('red'); break;
                case 'WEEKEND - HOLIDAY': $period->setColorcode('grey'); break;
                case 'Maintenance MOB/DEMOB': $period->setColorcode('blue'); break;
            }
            $manager->persist($period);
            $manager->flush();
            return $this->redirectToRoute('display_periods', [

            ]);
        }

        return $this->render('forms/form_period.html.twig', [
            'formPeriod' => $form->createView(),
            'period' => $period
        ]);
    }
}