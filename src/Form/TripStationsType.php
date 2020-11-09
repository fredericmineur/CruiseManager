<?php

namespace App\Form;

use App\Entity\Tripstations;
use App\Form\DataTransformer\StationToNumberTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripStationsType extends AbstractType
{

    private $transformer;

    public function __construct(StationToNumberTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Code'
                    ]
                ])

            ->add('deflatitude',
                NumberType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'DefLat'
                    ]
                ]
            )
            ->add('deflongitude',
                NumberType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'DefLong'
                    ]
                ]
                )
            ->add('stationnr',
                HiddenType::class
                )
            ->add('id', HiddenType::class)
        ;

        $builder->get('stationnr')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tripstations::class,
        ]);
    }
}
