<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/ad/{slug}/book", name="booking_create", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER", message="Vous n'est pas autorisé à être ici")
     */
    public function create(Ad $ad, Request $request, EntityManagerInterface $emi): Response
    {

        $booking = new Booking();

        $form = $this->createForm(BookingFormType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $booking->setBooker($this->getUser())->setAd($ad);

            // si les dates ne sont pas disponible, return error
            if (!$booking->isBookableDates()){
                $this->addFlash('warning', 'les dates que vous avez choisie sont déjà prises');
            }
            else{
                //sinon ok
                $emi->persist($booking);
                $emi->flush();
                return $this->redirectToRoute('booking_show', [
                    'id'=>$booking->getId(),
                    'withAlert' => true
                ]);
            }
        }
        return $this->render('booking/create.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }


    /**
     *
     * @Route("booking/{id}", name="booking_show")
     * @param Booking $booking
     * @return Response
     */
    public function show(Booking $booking){

        return $this->render('booking/show.html.twig',[
            'booking'=> $booking
            ]
        );
    }

}
