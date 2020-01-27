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
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class CruiseType extends AbstractType
{

    //SORTING THE TRIPS BY DATE for the edit form
//https://stackoverflow.com/questions/34967795/order-of-symfony-form-collectiontype-field/34968046
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        usort($view['trips']->children, function (FormView $a, FormView $b){
            $startDateA = $a->vars['value']->getStartdate();
            $startDateB = $b ->vars['value']->getStartdate();
            if ($startDateA == $startDateB) {
                return 0;
            }
            return ($startDateA < $startDateB) ? -1 : 1;
        });
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        dd($options);
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



            ->add('campaign', CollectionType::class,
                           [
                               'allow_add'=>true,
                               'allow_delete'=>true,
                               'by_reference'=>false,
                               'entry_type' => EntityType::class,
                               'entry_options' => [
                                   'label' => 'Campaign (IMIS + name)',
                               'class' => Campaign::class,
                               'required' => false,

                               'choice_label' => function($campaign) {
                                   return $campaign->getImisprojectnr().' '.$campaign->getCampaign();
                               },
                               'query_builder' => function(EntityRepository $er) {
                                   return $er->createQueryBuilder('i')
           //                            ->orderBy('i.campaign', 'ASC')
                                       ->orderBy('i.campaign', 'ASC')
                                       ;
                               }
                               ]

                           ])




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
                                   return $investigator->getSurname().', '.$investigator->getFirstname();
                               },
                               'query_builder' =>function (EntityRepository $er) {
                                   return $er->createQueryBuilder('i')
                                       ->orderBy('i.surname', 'ASC');
                               }
                           ]
                       )




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

                    'required' => false,
//                    'attr' => [
//                        'class' => 'trips-collection',
//                    ],
                 ]
            )
        ;

}








    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cruise::class,

//            'arrayCampaigns' => array()
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




//https://stackoverflow.com/questions/45169833/symfony-fill-choicetype-with-an-array

//           ->add('campaign',
//                CollectionType::class,
//                [
//
//                    'entry_type'=> ChoiceType::class,
////                    'allow_add'=> true,
////
////                    'allow_delete'=> true,
//                    'by_reference'=>false,
////
////                    'required' => false,
//                    'entry_options' => [
////                        'choices' => $options['arrayCampaigns']
//                        'choices'  => [
//                            'Nashville' => 'nashville',
//                            'Paris'     => 'paris',
//                            'Berlin'    => 'berlin',
//                            'London'    => 'london',
//                        ],
//                    ]
//
//                ]
//            )
//            dd($options['arrayCampaigns'])

//            ->add('campaign',
//                EntityType::class,
//                [
//                    'label' => 'Campaign',
//                    'class' => Campaign::class,
//                    'required' => false,
//                    'attr'=> [
//                        'placeholder' => 'xxxxxx'
//                    ],
//                    'choice_label' => function($campaign) {
//                        return utf8_encode( $campaign->getImisprojectnr().' '.$campaign->getCampaign());
//                    },
//                    'query_builder' => function(EntityRepository $er) {
//                        return $er->createQueryBuilder('i')
////                            ->orderBy('i.campaign', 'ASC')
//                            ->orderBy('i.imisprojectnr', 'ASC');
//                    }
//                ]
//            )

