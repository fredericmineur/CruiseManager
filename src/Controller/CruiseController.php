<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Investigators;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CruiseController extends AbstractController
{
    /**
     * @Route("/cruises", name="cruises_index")
     */
    public function index()
    {
        $repoCruise = $this->getDoctrine()->getRepository(Cruise::class);
//        $repoInvestigator = $this->getDoctrine()->getRepository(Investigators::class);
        $cruises = $repoCruise->findAll();
//        $investigators = $repoInvestigator->findAll();
        return $this->render('cruise/display_cruises.html.twig', [
            'cruises' => $cruises,
//            'investigators' => $investigators
        ]);
    }
}
