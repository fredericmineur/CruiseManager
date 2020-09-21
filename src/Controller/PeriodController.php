<?php


namespace App\Controller;


use App\Entity\Periods;
use App\Form\PeriodType;
use App\Repository\PeriodsRepository;
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
    public function periodsJsonForTable (SerializerInterface $serializer, PeriodsRepository $periodsRepository) {
//        $periodRepository = $this->getDoctrine()->getRepository(Periods::class);
        $periods = $periodsRepository->getPeriodsWithColor();
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
            'period' => $period,
            'mode' => 'new'
        ]);
    }

    /**
     * @Route("/periods/{periodId}/edit", name="edit_period", options={"expose"=true})
     */
    public function editPeriod(EntityManagerInterface $manager, Request $request, $periodId) {
        $period = $this->getDoctrine()->getRepository(Periods::class)
            ->findOneBy(['id' => $periodId]);
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
            'period' => $period,
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("periods/{periodId}/remove_warning", name="remove_period_warning", options={"expose"=true})
     */
    public function removePeriodWarning(EntityManagerInterface $manager, $periodId){
        $period = $manager->getRepository(Periods::class)
            ->findOneBy(['id' => $periodId]);
        return $this->render('remove/remove_period.html.twig', [
            'period' => $period
        ]);
    }

    /**
     * @Route("/periods/{periodId}/remove", name="remove_period")
     */
    public function removePeriod(EntityManagerInterface $manager, $periodId) {
        $period = $manager->getRepository(Periods::class)
            ->findOneBy(['id' => $periodId]);
        $manager->remove($period);
        $manager->flush();
        return $this->redirectToRoute('display_periods');
    }

}