<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;


class PagniationService extends AbstractController
{
    Private $entityClass;
    private $limit = 10;
    private $currentPage = 0;
    private $emi;
    private $twig;

    public function __construct(EntityManagerInterface $emi, Environment $twig)
    {
        $this->emi = $emi;
        $this->twig = $twig;
    }

    public function getData (){
        $repo =  $this->emi->getRepository($this->getEntityClass());
        return $repo->findBy([], [], $this->limit, $this->getOffSet());
    }

    public function getOffSet(){
        return $this->getPage() * $this->getLimit() - $this->getLimit();
    }

    public function getNbpage(){
        $repo =  $this->emi->getRepository($this->getEntityClass());

        $allads = $repo->findAll();
        return count($allads) / $this->getLimit();
    }

    public function setPage($page){
        if ($this->getPage() == 0){
            $page = 1;
        }
        $this->currentPage = $page;
            return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $entityClass
     */
    public function setEntityClass($entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

}