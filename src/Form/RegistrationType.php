<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class,
                $this->setLabelHolder("Prénom", "votre prénom"))
            ->add('lastName', TextType::class,
                $this->setLabelHolder('Nom', 'votre prénom..'))
            ->add('picture', UrlType::class,
                $this->setLabelHolder('Photo de profile','Url de votre photo de profile'))

            ->add('hash', RepeatedType::class,[
                'type' => PasswordType::class,
                'required'=>true,
                'options' => ['attr' => ['class' => 'password-field']],
                'invalid_message' => 'La répétition ne ce corresponde pas',
                'first_options'  => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'confirmation du mot de passe']
            ])
            ->add('introduction',TextType::class,
                $this->setLabelHolder('Quelque mots à propos de vous','Présentez vous en quelque mots'))
            ->add('email', EmailType::class,
                $this->setLabelHolder('Adresse mail', 'Votre addrese mail'))
            ->add('description', TextareaType::class,
                $this->setLabelHolder('Description détaillé', "c'est le moment de vous présenter en détails !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
