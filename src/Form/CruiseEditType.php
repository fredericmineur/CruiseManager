<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use App\Entity\Trip;
use App\Form\DataTransformer\Time17HTransformer;
use App\Form\DataTransformer\Time8HTransformer;
use App\Repository\CampaignRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CruiseEditType extends AbstractType
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

            //https://symfony.com/doc/current/reference/forms/types/collection.html

//           ->add('campaign',
//                CollectionType::class,
//                [
//                    'entry_type'=> ChoiceType::class,
//                    'entry_options' => [
//                        'choices'=> [
//
//                        ]
//                    ]
//
//                ]
//            )



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
//                    'attr' => [
//                        'class' => 'trips-collection',
//                    ],

                                   ]

            );





        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cruise::class,
        ]);
    }



//    public function getBlockPrefix()
//    {
//        return 'cruise';
//    }

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
