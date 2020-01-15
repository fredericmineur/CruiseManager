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

class PrincipalInvestigatorController extends AbstractController
{
    /**
     * @Route("/PI", name="principalinvestigators_index")
     */
    public function displayPIs (EntityManagerInterface $manager){
        $repoInvestigators = $manager->getRepository(Investigators::class);
        $investigators = $repoInvestigators->findBy([],['surname'=>'ASC']);
//        dd($investigators);
        return $this->render('display/display_PInvestigators.html.twig', [
            'principalInvestigators' => $investigators
        ]);
    }

    /**
     * @Route("/PI/create_investigator", name="create_investigator")
     */
    public function createPI (EntityManagerInterface $manager, Request $request){
        $investigator = new Investigators();
        $form = $this->createForm(InvestigatorsType::class, $investigator);
        $form->handleRequest($request);
//        dd($form);
        if($form->isSubmitted() && $form->isValid()){
//            dd($investigator);
            $manager->persist($investigator);
            $manager->flush();
            return $this->redirectToRoute('principalinvestigator_details', [
                'principalInvestigatorId' => $investigator->getInvestigatorid()
            ]);
        }
        return $this->render('forms/form_PInvestigator.html.twig', [
            'formInvestigator' => $form->createView(),
            'investigator' => $investigator,
            'mode' => 'create'
        ]);
    }

    /**
     * @Route("/PI/{investigatorId}", name="principalinvestigator_details")
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
//        dd($cruisesAsInvestigator);

//        dd($tripsAsInvestigators);
//        $cruisesIdAsInvestigator = [];
//        foreach ($tripsAsInvestigators as $tripsAsInvestigator) {
//            if(! in_array($cruisesIdAsInvestigator, $tripsAsInvestigator->getCruiseid())) {
//                array_push($cruisesIdAsInvestigator, $tripsAsInvestigator->getCruiseid());
//            }
//
//        }
//        dd($cruisesIdAsInvestigator);





        return $this->render('display/display_PInvestigator.html.twig', [
            'investigator' => $investigator,
            'cruisesPI'=> $cruisesPrincipalInvestigator,
            'cruisesAsInvestigator'=>$cruisesAsInvestigator,
            'tripAsInvestigator'=>$tripsAsInvestigator
        ]);
    }



    /**
     * @Route("/PI/edit/{principalInvestigatorId}", name="edit_principalinvestigator")
     */
    public function editPI(EntityManagerInterface $manager, Request $request, $principalInvestigatorId) {
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        $form = $this->createForm(InvestigatorsType::class, $investigator);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($investigator);
            $manager->flush();
            return $this->redirectToRoute('principalinvestigator_details', [
                'principalInvestigatorId' => $investigator->getInvestigatorid()
            ]);
        }
        return $this->render('forms/form_PInvestigator.html.twig', [
            'formInvestigator' => $form->createView(),
            'investigator' => $investigator,
            'mode' => 'edit'
        ]);
    }

    /**
     * @Route("/PI/remove_PI_warning/{principalInvestigatorId}", name="remove_PI_warning")
     */
    public function warnRemovePI (EntityManagerInterface $manager, $principalInvestigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        return $this->render('remove/remove_PI.html.twig',[
            'investigator' => $investigator
        ]);
    }



    /**
     * @Route("/PI/remove_PI/{principalInvestigatorId}", name="remove_PI")
     */
    public function removePI(EntityManagerInterface $manager, $principalInvestigatorId ){
        $principalInvestigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        $manager->remove($principalInvestigator);
        $manager->flush();
        return $this->redirectToRoute('principalinvestigators_index');
    }



}
