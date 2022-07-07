<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{

    /**
     * @Route ("admin/comments", name="admin_comments")
     * @param CommentRepository $cr
     * @return Response
     */
    public function indexCom(CommentRepository $cr){
        return $this->render('admin/comment/index.html.twig', ['comments' => $cr->findAll()]);
    }

    /**
     * @Route("admin/comment/edit/{id}", name="admin_comment_edit")
     * @param Comment $coment
     * @param Request $request
     * @param EntityManagerInterface $emi
     * @return Response
     */
    public function edit(Comment $coment, EntityManagerInterface $emi, Request $request){
        $form = $this->createForm(CommentType::class, $coment);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $emi->persist($coment);
            $emi->flush();
            $this->addFlash("success",
            "Le commentaire à bien été mis à jour");
            return $this->redirectToRoute('admin_comments');
        }

        return $this->render('admin/comment/edit.html.twig', ["form" => $form->createView(), 'comment' => $coment]);
    }

    /**
     * @Route ("admin/remove/comment/{id}", name="admin_remove_comment")
     * @param EntityManagerInterface $emi
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(EntityManagerInterface $emi, Comment $comment){

        $emi->remove($comment);
        $emi->flush();

        $this->addFlash('success', "commentaire supprimer");

        return $this->redirectToRoute('admin_comments');

    }
}
