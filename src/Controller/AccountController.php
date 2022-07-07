<?php

namespace App\Controller;

use App\Entity\UpdatePassword;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Form\UpdatePwdType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error    = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError'=>$error !== null,
            'ussername'=> $username
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     * @IsGranted("USER_ROLE")
     * @return void
     */
    public function logout(){

    }

    /**
     * @Route ("/register", name="accounte_register")
     *
     * @return Response
     */
    public function register(
        Request $request,
        EntityManagerInterface $emi,
        UserPasswordHasherInterface  $hasher
    ){
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $hashed = $hasher->hashPassword($user, $user->getHash());
            $user->setHash($hashed);
            $emi->persist($user);
            $emi->flush();
            $this->addFlash(
                'success',
                "votre comptes à bien été crée ! :o"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/resgistration.html.twig',[
            'form'=>$form->createView()
            ]
        );
    }

    /**
     * @route("/profile", name="profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(
        Request $request,
        EntityManagerInterface $emi
    ) : Response
    {

        $userToUpdate = $this->getUser();

        $form = $this->createForm(AccountType::class,$userToUpdate);

        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $emi->persist($userToUpdate);
            $emi->flush();
            $this->addFlash('success',
            'Vos données ont bien été mise à jours'
            );
        }
        return $this->render('account/profile.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/password-update", name="password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePwd(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $emi
    ){
        $pwdUpdate = new UpdatePassword;
        $form = $this->createForm(UpdatePwdType::class, $pwdUpdate);
        $user = $this->getUser();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$hasher->isPasswordValid( $user,$pwdUpdate->getOldPwd()) ){
                $form->get('oldPwd')
                    ->addError(new FormError(
                        'Le mots de passe que vous avez taper est pas le mot de passe acutel de ce compte.'
                    ));
            }
            else{
                $newPassword = $pwdUpdate->getNewPwd();
                $hash = $hasher->hashPassword($user, $newPassword);
                $user->setHash($hash);
                $emi->persist($user);
                $emi->flush();
                $this->addFlash('success',
                'Votre mot de passe à bien été changer.');
                $this->redirectToRoute('profile');
            }
        }
        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount() : Response
    {
        return $this->render('user/index.html.twig',[
            'user'=>$this->getUser()
        ]);
    }

    /**
     * display all booking of the users.
     *
     * @Route("/account/bookings", name="account_bookings")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function bookings(){

        return $this->render('account/bookings.html.twig',[
            'bookings' => $this->getUser()->getBookings()
        ]);
    }
}
