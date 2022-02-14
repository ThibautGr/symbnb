<?php

namespace App\Controller;

use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $adR
     */
    public function index(AdRepository $adR): Response
    {
        return $this->render('ad/index.html.twig', [
           'allAds'=> $adR->findAll()
        ]);
    }

    /**
     * return one ad
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show(string $slug, AdRepository $adR) : Response
    {

        $ad = $adR->findOneBy(array('slug'=>$slug));
        return $this->render('ad/show.html.twig',[
           'ad'=> $ad
        ]);
    }
}
