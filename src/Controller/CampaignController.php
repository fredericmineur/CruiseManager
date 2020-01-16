<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Entity\Tripinvestigators;
use App\Form\CampaignType;
use App\Form\CruiseEditType;
use App\Form\CruiseTrialType;
use App\Form\CruiseType;
use App\Form\TripType;
use App\Repository\CampaignRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;




class CampaignController extends AbstractController
{

    /**
     * @Route("/campaigns", name="campaigns_index")
     */
    public function campaignsIndex()
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaigns = $repoCampaigns->findAll();

        return $this->render('display/display_campaigns_index.html.twig', [
            'campaigns' => $campaigns
        ]);
    }


    /**
     * @Route("/campaign/new", name="campaign_create")
     */
    public function createCampaign(Request $request, EntityManagerInterface $manager)
    {


        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($campaign);
            $manager->flush();
            $this->addFlash(
                'success',
                "the campaign <strong>{$campaign->getCampaign()}</strong> has been successfully submitted"
            );
            return $this->redirectToRoute('campaign_details', [
                'campaignId' => $campaign->getCampaignid()
            ]);
        }

        return $this->render('forms/form_campaign_new.html.twig', [
            'formCampaign' => $form->createView(),
            'mode' => 'create'
        ]);
    }

    /**
     * @Route("/campaign/edit/{campaignId}", name="campaign_edit")
     */
    public function editCampaign($campaignId, Request $request, EntityManagerInterface $manager)
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaign = $repoCampaigns->findOneBy(['campaignid'=> $campaignId]);
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($campaign);
            $manager->flush();
            $this->addFlash(
                'success',
                "The campaign \"{$campaign->getCampaign()}\" has been successfully modified"
            );
            return $this->redirectToRoute('campaign_details', [
                'campaignId' => $campaign->getCampaignid()
            ]);
        }

        return $this->render('forms/form_campaign_edit.html.twig', [
            'formCampaign' => $form->createView()
        ]);
    }

    /**
     * @Route("/campaign/{campaignId}",options={"expose"=true}, name="campaign_details")
     */
    public function campaignDetails($campaignId)
    {
        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
        $campaign = $repoCampaigns->findOneBy(['campaignid'=> $campaignId]);
        $cruises = $campaign->getCruise();

        return $this->render('display/display_campaign.html.twig', [
                'campaign' => $campaign,
                'cruises' => $cruises
            ]);
    }

    /**
     * @Route("/campaign/remove_campaign_warning/{campaignId}", name="remove_campaign_warning")
     */
    public function warnRemoveCampaign (EntityManagerInterface $manager, $campaignId){
        $campaign = $manager->getRepository(Campaign::class)
            ->findOneBy(['campaignid'=> $campaignId]);
        return $this->render('remove/remove_campaign.html.twig',[
            'campaign' => $campaign
        ]);
    }

    /**
     * @Route("/campaign/remove_campaign/{campaignId}", name="remove_campaigm")
     */
    public function removeCampaign(EntityManagerInterface $manager, $campaignId ){
        $campaign = $manager->getRepository(Campaign::class)
            ->findOneBy(['campaignid'=> $campaignId]);
        $manager->remove($campaign);
        $manager->flush();
        return $this->redirectToRoute('campaigns_index');
    }

}


