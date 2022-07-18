<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsService extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    private $emi;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
    }

    public function getStats(){
        $users    = $this->getUser();
        $ads      = $this->getAds();
        $bookings = $this->getBokings();
        $comments = $this->getComments();

        return compact('users', 'ads', 'bookings', 'comments');
    }

    public function getUser()
    {
            return $this->emi->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAds(){
        return $this->emi->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getBokings(){
        return $this->emi->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getComments(){
        return $this->emi->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function get5BestAds(){
        return $this->emi->createQuery(
            'select AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM  App\Entity\Comment c
            join c.ad a
            join a.author u 
            group by a
            order by note DESC'
        )->setMaxResults(5)
        ->getResult();
    }

    public function get5worstAds(){
        return $this->emi->createQuery(
            'select AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM  App\Entity\Comment c
            join c.ad a
            join a.author u 
            group by a
            order by note ASC '
        )->setMaxResults(5)
            ->getResult();
    }

}