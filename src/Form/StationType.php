<?php

namespace App\Form;

use App\Entity\Stations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude',
                NumberType::class,[
                    'label' => 'Latitude',
                    'attr' => [
                        'placeholder' => 'DefLatitude'
                    ]
                ])
            ->add('longitude',
                NumberType::class, [
                'label' => 'Longitude',
                    'attr' => [
                        'placeholder' => 'DefLongitude'
                    ]
            ])

            ->add('code',
                TextType::class, [
                    'label' => 'Code',
                    'attr' => [
                        'placeholder' => 'Code'
                    ]
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stations::class,
        ]);
    }
}
