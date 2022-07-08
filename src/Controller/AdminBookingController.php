<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\AdminBookinType;
use App\Repository\BookingRepository;
use App\Repository\CommentRepository;
use App\Services\PagniationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route ("admin/bookins/{page}", name="admin_bookings")
     * @param CommentRepository $cr
     * @return Response
     */
    public function index(PagniationService $ps, $page = 1): Response
    {
        $ps->setPage(intval($page));
        $ps->setEntityClass(Booking::class);

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $ps->getData(),
            'nbPage'=> intval(round($ps->getNbpage())),
            'page' => $ps->getPage()
        ]);
    }

    /**
     * @Route("admin/booking/edit/{id}", name="admin_bookings_edit")
     * @param Booking $booking
     * @param EntityManagerInterface $emi
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Booking $booking, EntityManagerInterface $emi, Request $request){

        $form = $this->createForm(AdminBookinType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration());
            $emi->persist($booking);
            $emi->flush();
            $this->addFlash('succes',
            "la réservation n°{$booking->getId()} à bien été mis a jour");
            return $this->redirectToRoute('admin_bookings');
        }

        return $this->render('admin/booking/edit.html.twig', ["form"=>$form->createView(), "booking"=>$booking, 'ad' => $booking->getAd()]);
    }

    /**
     * @Route("admin/booking/delete/{id}", name="admin_booking_delete")
     *
     * @param Booking $booking
     * @param EntityManagerInterface $emi
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Booking $booking, EntityManagerInterface $emi){
        $emi->remove($booking);
        $emi->flush();
        $this->addFlash('success', "la réservation n°{$booking->getId()} à bien été supprimer");
        return $this->redirectToRoute('admin_bookings');
    }


}
