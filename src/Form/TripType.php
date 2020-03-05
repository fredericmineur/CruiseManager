<?php

namespace App\Form;


use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startdate',
                DateType::class,
                [
                    'widget'=>'single_text',
                    'label'=>'Start Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd',
                        'class' => 'js-datepicker'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Date()
                    ]
                ]
            )

            ->add(
                'enddate',
                DateType::class,
                [
                    'widget'=>'single_text',
                    'label'=>'End Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd',
                        'class' => 'js-datepicker'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Date(),
                        new GreaterThanOrEqual([
                            'propertyPath' =>'parent.all[startdate].data'
                        ])
                    ]
                ]
            )

            ->add('destinationarea',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Destination Area'
                ])

           ->add('tripinvestigators',
                CollectionType::class,
                [
                    'entry_type'=> TripinvestigatorsType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete'=> true,
                    'by_reference' => false,
                    'label'=> 'Investigators for the trip',
                    'entry_options' => [
                        //Avoid labels 0, 1, 2, 3, 4 to show for each entry
                        'label' =>false,
                    ]
                ]
            )

            ->add('tripstations',
                CollectionType::class,
                [
                    'entry_type'=> TripStationsType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'label' => 'Stations for the trip',
                    'entry_options' => [
                        'label' => false
                    ]
                ]

            )

            ->add('tripid', HiddenType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'error_bubbling' => true
        ]);
    }


}
