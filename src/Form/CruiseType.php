<?php

namespace App\Form;

use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Form\DataTransformer\Time17HTransformer;
use App\Form\DataTransformer\Time8HTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('startdate',
                DateType::class,

                [
                    'label' => 'Start date',
//                    'required' => false,
//                    'html5' => false,
                    'widget' => 'single_text',
//                    'format' => 'dd/mm/yyyy',
//                    'attr' => [
//                        'class' => 'datepicker',
//                        'data-provide' => 'datepicker'
//                    ],
//                    'label_attr' => [
//                        'data-inspire-required' => '1'
                    ]
//
            )
            ->add('enddate',
                DateType::class, [
                    'label' => 'End Date',
                    'attr' => [
                        'placeholder' => 'd/m/Y'
                    ]
                ])

//                [
//                    'widget' => 'single_text',
//                    'format' => 'yyyy-MM-dd'
//                ]

            ->add('destination',
                TextType::class, [
                    'label' => 'Destination',
                    'attr' => [
                        'placeholder' => 'Destination'
                    ],
                    'required' => false
                ])

            ->add('memo',
                TextareaType::class, [
                    'label' => 'Notes',
                    'attr' => [
                        'placeholder' => 'Memo'
                    ],
                    'required' => false
                ])

            ->add('plancode',
                TextType::class,[
                    'label' => 'Plan Code',
                    'attr' => [
                        'placeholder' => 'xx-xxx'
                    ],
                    'required' => false
                ])

            ->add('ship',
                TextType::class,[
                    'label' => 'Ship',
                    'attr' => [
                        'placeholder' => 'Ship'
                        ],
                    'required' => false
                ]

                )
            ->add('purpose',
                TextType::class, [
                    'label' => 'Purpose',
                    'attr' =>[
                        'placeholder' => 'Purpose'
                    ],
                    'required' => false
                ])

//            ->add('campaign')
            ->add('principalinvestigator',  //https://stackoverflow.com/questions/37617786/combine-columns-in-choice-list-symfony-form
                EntityType::class, [
                    'label' => 'Principal Investigator',
                    'class' => Investigators::class,
//                    'multiple' => true,
//                    'expanded' => true,
                    'choice_label' => function($investigator){
                        return utf8_encode($investigator->getSurname().', '.$investigator->getFirstname());
                    },
                    'query_builder' =>function(EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->orderBy('i.surname', 'ASC');
                    }
                ])
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
