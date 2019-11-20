<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Form\DataTransformer\Time17HTransformer;
use App\Form\DataTransformer\Time8HTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CruiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'plancode',
                TextType::class,
                [
                    'label' => 'Plan Code',
                    'attr' => [
                        'placeholder' => 'xx-xxx'
                    ],
                    'required' => false
                ]
            )

         /*  ->add('campaign',
                EntityType::class,
                [
                    'label' => 'Campaign',
                    'class' => Campaign::class,
                    'required' => false,
                    'attr'=> [
                        'placeholder' => 'xxxxxx'
                    ],
                    'choice_label' => function($campaign) {
                        return utf8_encode( $campaign->getImisprojectnr().' '.$campaign->getCampaign());
                    },
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('i')
//                            ->orderBy('i.campaign', 'ASC')
                            ->orderBy('i.imisprojectnr', 'ASC');
                    }
                ]
            )*/

            ->add(
                // CHECK https://ourcodeworld.com/articles/read/652/how-to-create-a-dependent-select-dependent-dropdown-in-symfony-3
                'principalinvestigator',
                //https://stackoverflow.com/questions/37617786/combine-columns-in-choice-list-symfony-form
                EntityType::class,
                [
                    'label' => 'Principal Investigator',
                    'class' => Investigators::class,
                    //                    'multiple' => true,
                    //                    'expanded' => true,
                    'required' => false,
                    'attr'=> [
                        'placeholder' => 'xxxxxx'
                    ],
                    'choice_label' => function ($investigator) {
                        return utf8_encode($investigator->getSurname().', '.$investigator->getFirstname());
                    },
                    'query_builder' =>function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->orderBy('i.surname', 'ASC');
                    }
                ]
            );
//            ->add(
//                'startdate',
//                DateType::class,
//                [
//                    'label' => 'Start date',
//                //                    'required' => false,
//                //                    'html5' => false,
//                    'widget' => 'single_text',
//                //                    'format' => 'dd/mm/yyyy',
//                //                    'attr' => [
//                //                        'class' => 'datepicker',
//                //                        'data-provide' => 'datepicker'
//                //                    ],
//                //                    'label_attr' => [
//                //                        'data-inspire-required' => '1'
//                    ]
//                //
//            )
//            ->add(
//                'enddate',
//                DateType::class,
//                [
//                    'label' => 'End Date',
//                    'attr' => [
//                        'placeholder' => 'd/m/Y'
//                    ]
//                ]
//            )

           $builder ->add(
                'trips',
                CollectionType::class,
                [
                    'entry_type' => TripType::class,
                    'allow_add' => true,

//                    'delete_empty' => function(Trip $trip){
//                        return null === $trip || empty($trip->getStartdate());
//                    },
                    'allow_delete' => true,

                    'by_reference' => false,
//                    'required' => false,

                                   ]


            );


//            ->add(
//                'destination',
//                TextType::class,
//                [
//                    'label' => 'Destination',
//                    'attr' => [
//                        'placeholder' => 'Destination'
//                    ],
//                    'required' => false
//                ]
//            )

//            ->add(
//                'memo',
//                TextareaType::class,
//                [
//                    'label' => 'Notes',
//                    'attr' => [
//                        'placeholder' => 'Memo'
//                    ],
//                    'required' => false
//                ]
//            )
//
//
//
//            ->add(
//                'ship',
//                TextType::class,
//                [
//                    'label' => 'Ship',
//                    'attr' => [
//                        'placeholder' => 'Ship'
//                        ],
//                    'required' => false
//                ]
//            )
//            ->add(
//                'purpose',
//                TextType::class,
//                [
//                    'label' => 'Purpose',
//                    'attr' =>[
//                        'placeholder' => 'Purpose'
//                    ],
//                    'required' => false
//                ]
//            )



        ;
//        $builder -> get('startdate')
//            ->addModelTransformer(new Time8HTransformer());
//        $builder ->get('enddate')
//            ->addModelTransformer(new Time17HTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cruise::class,
        ]);
    }

//    public function getConfiguration($label, $placeholder)
//    {
//        return [
//            'label' => $label,
//            'attr' => [
//                'placeholder' => $placeholder
//            ]
//        ];
//    }
}
