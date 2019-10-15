<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MidasManagementHomeController extends AbstractController
{
    /**
     * @Route("/", name="midas_management_home")
     */
    public function index()
    {
        return $this->render('midas_management_home/home.html.twig', [
            'controller_name' => 'MidasManagementHomeController',
        ]);
    }

}
