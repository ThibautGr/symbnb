<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\BookingRepository;
use App\Repository\UserRepository;
use App\Services\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route ("admin/dashboard", name="admin_dashboard")
     * @param AdRepository $ar
     * @return Response
     */
    public function index(AdRepository $ar, UserRepository $ur, BookingRepository $br,EntityManagerInterface $emi, StatsService $ss){



        $bestAds = $ss->get5BestAds();
        $worstAds = $ss->get5worstAds();


        return $this->render('admin/dashboard/index.html.twig',
            [
                'stats'=> $ss->getStats(),
                'bestAds' => $bestAds,
                'worstAds' => $worstAds
            ]
        );
    }
}
