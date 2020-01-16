<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\InvestigatorsType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InvestigatorController extends AbstractController
{
    /**
     * @Route("/investigators", name="investigators_index")
     */
    public function displayInvestigators (EntityManagerInterface $manager){
        $repoInvestigators = $manager->getRepository(Investigators::class);
        $investigators = $repoInvestigators->findBy([],['surname'=>'ASC']);
        return $this->render('display/display_investigators.html.twig', [
            'investigators' => $investigators
        ]);
    }

    /**
     * @Route("/investigators/create_investigator", name="create_investigator")
     */
    public function createPI (EntityManagerInterface $manager, Request $request){
        $investigator = new Investigators();
        $form = $this->createForm(InvestigatorsType::class, $investigator);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($investigator);
            $manager->flush();
            return $this->redirectToRoute('investigator_details', [
                'investigatorId' => $investigator->getInvestigatorid()
            ]);
        }
        return $this->render('forms/form_investigator.html.twig', [
            'formInvestigator' => $form->createView(),
            'investigator' => $investigator,
            'mode' => 'create'
        ]);
    }

    /**
     * @Route("/investigators/{investigatorId}", name="investigator_details")
     */
    public function displayPI(EntityManagerInterface $manager, $investigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $investigatorId]);
//        $cruises = $principalInvestigator->getCruises();
        $cruisesPrincipalInvestigator = $manager->getRepository(Cruise::class)->findBy(['principalinvestigator'=> $investigator->getInvestigatorid()], ['plancode'=> 'ASC']);
        $tripInvestigatorsAsInvestigator = $manager->getRepository(Tripinvestigators::class)->findBy(['investigatornr'=>$investigator->getInvestigatorid()], ['id'=>'ASC']);
//        dump($tripInvestigatorsAsInvestigator);
        $tripsAsInvestigator = [];
        foreach ($tripInvestigatorsAsInvestigator as $tripInvestigatorAsInvestigator) {
            array_push($tripsAsInvestigator, $tripInvestigatorAsInvestigator->getTripnr());
        }
        dump($tripsAsInvestigator);
        $cruisesAsInvestigator=[];
        foreach ($tripsAsInvestigator as $trip){
//            dump($trip->getCruiseid());
            if(! in_array( $trip->getCruiseid(), $cruisesAsInvestigator)){
                array_push($cruisesAsInvestigator, $trip->getCruiseid());
            }
        }

        return $this->render('display/display_investigator.html.twig', [
            'investigator' => $investigator,
            'cruisesPI'=> $cruisesPrincipalInvestigator,
            'cruisesAsInvestigator'=>$cruisesAsInvestigator,
            'tripAsInvestigator'=>$tripsAsInvestigator
        ]);
    }



    /**
     * @Route("/investigators/edit/{investigatorId}", name="edit_investigator")
     */
    public function editPI(EntityManagerInterface $manager, Request $request, $principalInvestigatorId) {
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        $form = $this->createForm(InvestigatorsType::class, $investigator);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($investigator);
            $manager->flush();
            return $this->redirectToRoute('investigator_details', [
                'investigatorId' => $investigator->getInvestigatorid()
            ]);
        }
        return $this->render('forms/form_investigator.html.twig', [
            'formInvestigator' => $form->createView(),
            'investigator' => $investigator,
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/investigators/remove_warning/{investigatorId}", name="remove_investigator_warning")
     */
    public function warnRemoveInvestigator (EntityManagerInterface $manager, $principalInvestigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        return $this->render('remove/remove_PI.html.twig',[
            'investigator' => $investigator
        ]);
    }



    /**
     * @Route("/investigators/remove_PI/{investigatorId}", name="remove_investigator")
     */
    public function removePI(EntityManagerInterface $manager, $principalInvestigatorId ){
        $principalInvestigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        $manager->remove($principalInvestigator);
        $manager->flush();
        return $this->redirectToRoute('investigators_index');
    }



}
