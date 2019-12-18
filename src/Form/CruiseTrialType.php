<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Entity\Investigators;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CruiseTrialType extends AbstractType
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



//            ->add('campaign', CollectionType::class,
//                [
//                    'allow_add'=>true,
//                    'allow_delete'=>true,
//                    'by_reference'=>false,
//                    'entry_type' => EntityType::class,
//                    'entry_options' => [
//                        'label' => 'Campaign (IMIS + name)',
//                        'class' => Campaign::class,
//                        'required' => false,
//
//                        'choice_label' => function($campaign) {
//                            return utf8_encode($campaign->getImisprojectnr().' '.$campaign->getCampaign());
//                        },
//                        'query_builder' => function(EntityRepository $er) {
//                            return $er->createQueryBuilder('i')
//                                //                            ->orderBy('i.campaign', 'ASC')
//                                ->orderBy('i.campaign', 'ASC')
//                                ;
//                        }
//                    ]
//
//                ])




//            ->add(
//            // CHECK https://ourcodeworld.com/articles/read/652/how-to-create-a-dependent-select-dependent-dropdown-in-symfony-3
//                'principalinvestigator',
//                //https://stackoverflow.com/questions/37617786/combine-columns-in-choice-list-symfony-form
//                EntityType::class,
//                [
//                    'label' => 'Principal Investigator',
//                    'class' => Investigators::class,
//                    //                    'multiple' => true,
//                    //                    'expanded' => true,
//                    'required' => false,
//                    'attr'=> [
//                        'placeholder' => 'xxxxxx'
//                    ],
//                    'choice_label' => function ($investigator) {
//                        return utf8_encode($investigator->getSurname().', '.$investigator->getFirstname());
//                    },
//                    'query_builder' =>function (EntityRepository $er) {
//                        return $er->createQueryBuilder('i')
//                            ->orderBy('i.surname', 'ASC');
//                    }
//                ]
//            )




            ->add(
                'trips',
                CollectionType::class,

                [
                    'label'=>false,
                    'entry_type' => TripType::class,
                    'entry_options' => ['label'=> false],
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
}
