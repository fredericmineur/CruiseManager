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
use Symfony\Component\Serializer\SerializerInterface;


class CampaignController extends AbstractController
{

    /**
     * @Route("/campaigns", name="campaigns_index")
     */
    public function campaignsIndex()
    {
//        $repoCampaigns = $this->getDoctrine()->getRepository(Campaign::class);
//        $campaigns = $repoCampaigns->findAll();
//
//        return $this->render('display/display_campaigns_index.html.twig', [
//            'campaigns' => $campaigns
//        ]);

        return $this->render('display/display_campaignsListAjax.html.twig', []);
    }

    //Postman on localhost : 33.98s, 7.03s, 14.66s, 25.69s, 7.06s
    /**
     * @Route("/api/getcampaigns", name="api_get_campaigns")
     */
    public function serializeCampaignList(SerializerInterface $serializer, CampaignRepository $campaignRepository, EntityManagerInterface $em)
    {
        $campaigns = $campaignRepository->getCampaignsWithCruises();
        $jsonCampaigns = $serializer->serialize($campaigns, 'json' );


        return new JsonResponse($jsonCampaigns, 200, [], true);
    }


    //Postman on localhost : 6.98s, 32s, 6.87s, 32.29s, 32.46s
    /**
     * @Route("/api/getcampaignsslim", name="api_get_campaigns_slim")
     */
    public function getCampaignList (CampaignRepository $campaignRepository)
    {
        $campaigns = $campaignRepository->getSlimCampaigns();

        $campaignsWitCruises = $campaignRepository->getCampaignsWithCruises();
//
        foreach($campaignsWitCruises as $singleCampaignWithCruises) {
            $campaignKey = array_search($singleCampaignWithCruises['campaignid'], array_column($campaigns, 'campaignid'));
            //hardly any difference in query time with or with unsetting elements
            foreach ($singleCampaignWithCruises['cruise'] as $k=>$v){
                unset($singleCampaignWithCruises['cruise'][$k]['startdate']);
                unset($singleCampaignWithCruises['cruise'][$k]['enddate']);
                unset($singleCampaignWithCruises['cruise'][$k]['destination']);
                unset($singleCampaignWithCruises['cruise'][$k]['memo']);
                unset($singleCampaignWithCruises['cruise'][$k]['ship']);
                unset($singleCampaignWithCruises['cruise'][$k]['purpose']);
            }
            $campaigns[$campaignKey]['cruise']=$singleCampaignWithCruises['cruise'];
        }

        return new JsonResponse($campaigns, 200);
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


