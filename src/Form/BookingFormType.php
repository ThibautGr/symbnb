<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingFormType extends ApplicationType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate',TextType::class, $this->setLabelHolder('date à laquelle vous souhaitez venir',"date à laquelle vous compter arriver"))
            ->add('endTime',TextType::class ,$this->setLabelHolder('Date à laquel vous souhaitez partir', 'date à laquel vous allez partir.'))
            ->add('comment', TextareaType::class, $this->setLabelHolder(false,
            'Nous vous invitons à commentez votre demande.',['required' =>false]))
        ;
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endTime')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
