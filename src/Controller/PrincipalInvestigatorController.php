<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Form\InvestigatorsType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PrincipalInvestigatorController extends AbstractController
{
    /**
     * @Route("/PI", name="principalinvestigators_index")
     */
    public function displayPIs (ObjectManager $manager){
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
    public function createPI (ObjectManager $manager, Request $request){
        $investigator = new Investigators();
        $form = $this->createForm(InvestigatorsType::class);
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
            'mode' => 'create'
        ]);
    }

    /**
     * @Route("/PI/{principalInvestigatorId}", name="principalinvestigator_details")
     */
    public function displayPI(ObjectManager $manager, $principalInvestigatorId){
        $principalInvestigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        return $this->render('display/display_PInvestigator.html.twig', [
            'principalInvestigator' => $principalInvestigator
        ]);
    }



    /**
     * @Route("/PI/edit/{principalInvestigatorId}", name="edit_principalinvestigator")
     */
    public function editPI(ObjectManager $manager, Request $request, $principalInvestigatorId) {
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
    public function warnRemovePI (ObjectManager $manager, $principalInvestigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        return $this->render('remove/remove_PI.html.twig',[
            'investigator' => $investigator
        ]);
    }



    /**
     * @Route("/PI/remove_PI/{principalInvestigatorId}", name="remove_PI")
     */
    public function removePI(ObjectManager $manager, $principalInvestigatorId ){
        $principalInvestigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $principalInvestigatorId]);
        $manager->remove($principalInvestigator);
        $manager->flush();
        return $this->redirectToRoute('principalinvestigators_index');
    }



//    public function cruiseDuration(ObjectManager $manager, $cruiseId)
//    {
////        $repoCruises = $this->getDoctrine()->getRepository(Cruise::class);
//        $cruise = $manager->getRepository(Cruise::class)->findOneBy(['cruiseid'=>$cruiseId]);
//        $trips = $this->getDoctrine()->getRepository(Trip::class)
//            ->findBy(['cruiseid'=> $cruiseId], ['startdate'=>'ASC']);
//
//        //Getting start and end date of the cruise (through the trips collection)
//        $startDates = [];
//        $endDates = [];
//        foreach ($trips as $trip){
//            array_push($startDates, $trip->getStartdate());
//            array_push($endDates, $trip->getEnddate());
//        }
//
//        if ($startDates){
//
//        }
//        $cruiseStartDate = !empty($startDates) ?  min($startDates) : null; //if $starDates is not empty
//        $cruiseEndDate = !empty($endDates) ? max($endDates) : null;
////        dump($cruiseStartDate, $cruiseEndDate);
//
//        return $this->render('display/display_cruise.html.twig', [
//            'campaigns' => $campaigns,
//            'cruise' => $cruise,
//            'trips' => $trips,
//            'cruiseStartDate' => $cruiseStartDate,
//            'cruiseEndDate' => $cruiseEndDate
//        ]);
//    }

}
