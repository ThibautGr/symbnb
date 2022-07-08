<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Services\PagniationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route ("/admin/ads/{page}", name="admin_ads_index", requirements={"page": "\d+"})
     * @return Response
     */
    public function index(PagniationService $ps, $page = 1): Response
    {
        $ps->setPage(intval($page));
        $ps->setEntityClass(Ad::class);

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $ps->getData(),
            'nbPage'=> intval(round($ps->getNbpage())),
            'page' => $ps->getPage()
        ]);
    }

    /**
     * @Route ("/admin/ad/{id}/edit", name="admin_ad_edit")
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request,EntityManagerInterface $emi){

        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $emi->persist($ad);
            $emi->flush();

            $this->addFlash('succes',
            "l'annonce à bien été mis a jour");
        }

        return $this->render('admin/ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * @Route ("admin/ad/delete/{id}", name="admin_ad_delete")
     * @param Ad $ad
     *
     */
    public function deleteAd(Ad $ad, EntityManagerInterface $emi){

        if (count($ad->getBookings()) != 0){
            $this->addFlash('alert',
                "annonce {$ad->getTitle()} est réserver, vous ne pouvez pas la supprimer"
            );
            return $this->redirectToRoute('admin_ads_index');
        }
        $emi->remove($ad);

        $emi->flush();
        $this->addFlash('succes',
            "annonce {$ad->getTitle()} bien supprimer"
        );
        return $this->redirectToRoute('admin_ads_index');
    }
}
