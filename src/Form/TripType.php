<?php

namespace App\Form;


use App\Entity\Trip;
use App\Entity\Tripstations;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

            ->add('tripid', HiddenType::class)




//            ->add('stations', CollectionType::class,[
//                'allow_add' => true,
//                'by_reference'=>false,
//                'entry_type'=> EntityType::class,
//                'entry_options' => [
//                    'label' => 'Station code',
//                    'class' => Stations::class,
//                    'required' =>false,
//                    'choice_label' => function($station){
//                        return $station->getCode();
//                    },
//                    'query_builder' => function(EntityRepository $er) {
//                        return $er->createQueryBuilder('s')
//                            ->orderBy('s.code', 'ASC');
//                    }
//                ]
//            ])



//            ->add('startpoint')
//            ->add('endpoint')
//
//            ->add('memo')
//            ->add('status')
//            ->add('logtext')
//            ->add('ship')
//            ->add('insync')
//            ->add('gpsstart')
//            ->add('gpsstop')
//            ->add('traveldistance')
//            ->add('leaveportdate')
//            ->add('arriveportdate')

        ;



//        $builder->add('tripid', TextType::class, [
//            'attr' => [
//                'readonly' => true,
//                'autocomplete' => 'off',
//            ],
//        ]);
//            ->add('cruiseid')

//            ->add('startpoint',
//                TextType::class, [
//                    'label' => 'Start point',
//                    'attr' => [
//                        'placeholder' => 'Call port'
//                    ]
//                ])
//
//            ->add('endpoint',
//                TextType::class, [
//                    'label' => 'End point',
//                    'attr' => [
//                        'placeholder' => 'Call port'
//                    ]
//                ])
//
//            ->add('destinationarea',
//                TextType::class, [
//                    'label' => 'Destination area',
//                    'attr' => [
//                        'placeholder' => 'Destination area'
//                    ]
//                ])
//
//            ->add('memo',
//                TextareaType::class, [
//                    'label' => 'Notes',
//                    'attr' => [
//                        'placeholder' => 'Memo'
//                    ]
//                ])

//            ->add('status', //DONE or CANCELLED
//                TextType::class,[
//                    'label' => 'Status',
//                    'attr' => [
//                        'placeholder' => 'Status'
//                    ]
//                ])
//
//            ->add('logtext',
//                TextareaType::class, [
//                    'label' => 'Log Text',
//                    'attr' => [
//                        'placeholder' => 'Log text'
//                    ]
//                ])
//
//            ->add('ship',
//                TextType::class, [
//                    'label' =>'Ship',
//                    'attr' => [
//                        'placeholder' => 'Ship'
//                    ]
//                ])
//
//            ->add('insync', //bit
//                CheckboxType::class, [
//                    'label' => 'Insync',
//                    'required' => false
//                ]
//            )
//            ->add('gpsstart',
//                DateTimeType::class,[
//                    'label' => 'GPS start time',
//                    'attr' => [
//                        'placeholder' => 'GPS start time'
//                    ]
//                ])
//
//            ->add('gpsstop',
//                DateTimeType::class, [
//                    'label' => 'GPS stop time',
//                    'attr' => [
//                        'placeholder' => 'GPS stop time'
//                    ]
//                ])
//
//            ->add('traveldistance',
//                NumberType::class, [
//                    'label' => 'Tavel distance',
//                    'attr' => [
//                        'placeholder' => ''
//                    ]
//                ])

//            ->add('leaveportdate',
//                DateTimeType::class
//                )
//            ->add('arriveportdate')

            //https://symfonycasts.com/screencast/collections/entity-type-checkboxes
            //https://symfonycasts.com/screencast/symfony-forms/entity-type
//            ->add('cruiseid', EntityType::class, [
////                'class' => Cruise::class,
////                'multiple' => true,
////                'expanded' => true
////            ])
//         ;

//        $builder -> get('startdate')
//        ->addModelTransformer(new Time8HTransformer());
//        $builder ->get('enddate')
//            ->addModelTransformer(new Time17HTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
            'error_bubbling' => true
        ]);
    }

//    public function getBlockPrefix()
//    {
//        return 'trip';
//    }
}
