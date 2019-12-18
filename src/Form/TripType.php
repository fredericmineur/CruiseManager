<?php

namespace App\Form;

use App\Entity\Cruise;
use App\Entity\Trip;
use App\Form\DataTransformer\Time17HTransformer;
use App\Form\DataTransformer\Time8HTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startdate',
                DateTimeType::class,
                [
                    'widget'=>'single_text',
                   'label'=>'Start Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd'
                    ]
                ]
                //                [
                //                    'label' => 'Start Date',
                //                    'attr' => [
                //                        'placeholder' => 'd/m/Y'
                //                    ]
                //                ]
            )

            ->add(
                'enddate',
                DateTimeType::class,
                [
                    'widget'=>'single_text',
                    'label'=>'End Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd'
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
                    'allow_delete'=> true,
                    'by_reference' => false,
                    'label'=> 'Investigators for the trip',
                    'entry_options' => [
                        //Avoid labels 0, 1, 2, 3, 4 to show for each entry
                        'label' =>false,
                    ]
                ]
            )



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
        ]);
    }

//    public function getBlockPrefix()
//    {
//        return 'trip';
//    }
}
