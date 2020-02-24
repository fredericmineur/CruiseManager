<?php

namespace App\Controller;

use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\InvestigatorsType;
use App\Repository\InvestigatorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InvestigatorController extends AbstractController
{
    /**
     * @Route("/investigators", name="investigators_index")
     */
    public function displayInvestigators (EntityManagerInterface $manager){
//        $repoInvestigators = $manager->getRepository(Investigators::class);
//        $investigators = $repoInvestigators->findBy([],['surname'=>'ASC']);
//        return $this->render('display/display_investigators.html.twig', [
//            'investigators' => $investigators
//        ]);

        return $this->render('display/display_investigatorsListAjax.html.twig');
    }


    /**
     * @Route("/api/getinvestigators_names", name="get_investigators_names", methods={"GET"})
     */
    public function getInvestigatorsJson (SerializerInterface $serializer, InvestigatorsRepository $investigatorsRepository)
    {
        $investigators = $investigatorsRepository->findAll();

        $jsonInvestigators = $serializer->serialize($investigators, 'json', ['groups'=>"get_investigators_names"]);

        return new JsonResponse($jsonInvestigators, 200, [], true);
    }

    /**
     * @Route("/api/getinvestigators", name="get_investigators", methods={"GET"})
     */
    public function getInvestigatorsTable (SerializerInterface $serializer, InvestigatorsRepository $investigatorsRepository, EntityManagerInterface $em)
    {
        $investigators = $investigatorsRepository->getAllInvestigatorsForTablePlus($em);
        $jsonInvestigators = $serializer->serialize($investigators, 'json');

        return new JsonResponse($jsonInvestigators, 200, [], true);
    }


    /**
     * @Route("/investigators/new/{surname}-{firstname}", name="create_investigator", defaults={"surname"=null, "firstname"=null}, options={"expose"=true})
     */
    public function createInvestigator (EntityManagerInterface $manager, Request $request, $surname, $firstname){
        $investigator = new Investigators();
        $investigator->setSurname($surname)->setFirstname($firstname);
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
     * @Route("/investigators/display/{investigatorId}", name="investigator_details", options={"expose"=true})
     */
    public function displayInvestigator (EntityManagerInterface $manager, $investigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $investigatorId]);

        $cruisesPrincipalInvestigator = $manager->getRepository(Cruise::class)->findBy(['principalinvestigator'=> $investigator->getInvestigatorid()], ['plancode'=> 'ASC']);
        $tripInvestigatorsAsInvestigator = $manager->getRepository(Tripinvestigators::class)->findBy(['investigatornr'=>$investigator->getInvestigatorid()], ['id'=>'ASC']);

        $tripsAsInvestigator = [];
        foreach ($tripInvestigatorsAsInvestigator as $tripInvestigatorAsInvestigator) {
            array_push($tripsAsInvestigator, $tripInvestigatorAsInvestigator->getTripnr());
        }

        $cruisesAsInvestigator=[];
        foreach ($tripsAsInvestigator as $trip){

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
     * @Route("/investigators/edit/{investigatorId}", name="edit_investigator", options={"expose"=true})
     */
    public function editPI(EntityManagerInterface $manager, Request $request, $investigatorId) {
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $investigatorId]);
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
     * @Route("/investigators/remove_warning/{investigatorId}", name="remove_investigator_warning", options={"expose"=true})
     */
    public function warnRemoveInvestigator (EntityManagerInterface $manager, $investigatorId){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $investigatorId]);
        return $this->render('remove/remove_investigator.html.twig',[
            'investigator' => $investigator
        ]);
    }



    /**
     * @Route("/investigators/remove_PI/{investigatorId}", name="remove_investigator")
     */
    public function removePI(EntityManagerInterface $manager, $investigatorId ){
        $investigator = $manager->getRepository(Investigators::class)
            ->findOneBy(['investigatorid'=> $investigatorId]);
        $manager->remove($investigator);
        $manager->flush();
        return $this->redirectToRoute('investigators_index');
    }

    /**
     * @Route("/investigators/getInvestigatorNames/{query}", name="get_investigator_names", options={"expose"=true})
     */
    public function  getInvestigatorNames ($query, InvestigatorsRepository $investigatorsRepository,
        SerializerInterface $serializer)
    {
        if($query!==null){
            $data = $investigatorsRepository->findByName($query);
        } else {
            $data= $investigatorsRepository->giveAllNames();
        }

        $data=$serializer->serialize($data,'json');

        return new JsonResponse($data, 200, [], true );

    }
}
