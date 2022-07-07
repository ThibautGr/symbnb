<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class   AdminBookinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class,['widget' =>'single_text', 'format' => 'dd-MM-yyyy', 'html5' => false])
            ->add('endTime', DateType::class,['widget' =>'single_text', 'format' => 'dd-MM-yyyy', 'html5' => false])
            ->add('comment')
            ->add('booker',EntityType::class,[
                'class' => User::class
            ])
            ->add('ad', EntityType::class, ['class'=> Ad::class])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
