<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route ("/ads/create/", name="ads_create")
     * @param Request $request
     * @param EntityManagerInterface $emi
     */

    public function create(EntityManagerInterface $emi,Request $request){

        $ad = new Ad();

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            foreach ($ad->getImages() as $image){
                $image->setAd($ad);
                $emi->persist($image);
            }

            $emi->persist($ad);
            $emi->flush();
            $this->addFlash('success',"l'annonce à bien été enregistrée !");
            return $this->redirectToRoute('ads_show',[
               'slug'=> $ad->getSlug()
            ]);
        }


        return $this->render('ad/create.html.twig',[
            'form'=>$form->createView()
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

    /**
     * @route( "/ads/{slug}/edit", name="edit_ads", methods={"GET","POST"})
     */
    public function edit(
        AdRepository $ar,
        EntityManagerInterface $emi,
        string $slug,
        Ad $a,
        Request $request
    ){


        $form = $this->createForm(AdType::class,$a);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $emi->persist($a);
            $emi->flush();
            $this->addFlash('success','ad a bien été modifier');
            $this->redirectToRoute('ads_index');
        }
        return $this->render('ad/edit.html.twig',[
                "form"=>$form->createView()
        ]);
    }


}
