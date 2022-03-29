<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{

    /**
     * @param string $label
     * @param ?string $placeholder
     * @param  array $option
     * @return array
     */
    public function setLabelHolder(string $label, string $placeholder = null, $option = []) : array
    {
        return array_merge([
            'label' => $label,
            'attr'  => [
                'placeholder' => $placeholder
            ]
        ], $option);
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',
                TextType::class,
                $this->setLabelHolder('Titre','Taper un super titre bien attréyant'))
            ->add('slug',
                TextType::class,
                $this->setLabelHolder('Adresse web', 'Taper l\'adresse du site web (automatique'),
                ['required' => false])
            ->add('price',
                MoneyType::class,
                $this->setLabelHolder('Prix par nuit','Indiquer le prix que vous voulez pour une nuit.'))
            ->add('introduction',
                TextType::class,
                $this->setLabelHolder('Introdcution','Donnez une description global de votre appartement'))
            ->add('content',
                TextareaType::class,
                $this->setLabelHolder('Description','Dites nous tous.'))
            ->add('coverImage',
                UrlType::class,
                $this->setLabelHolder('URL de l\'image', 'donnez une adresse d\'image qui donne envie'))
            ->add('rooms',
                IntegerType::class,
                $this->setLabelHolder('Nombre', 'Nombre de chambre le logement.'))
            ->add('images',
                CollectionType::class,
            [
                'entry_type' => ImageType::class,
                'allow_add' =>true
            ])
            ->add('save',SubmitType::class, ['label'=>'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
