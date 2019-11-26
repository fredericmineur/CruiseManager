<?php

namespace App\Form;

use App\Entity\Tripinvestigators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripinvestigatorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('shortname')
            ->add('surname',
                TextType::class,
                [
                    'label' => 'Surname',
                    'attr' => [
                        'placeholder' => 'Surname'
                    ]

                ]
                )
            ->add('firstname',
                TextType::class,
                [
                    'label' => 'First Name',
                    'attr' => [
                        'placeholder' => 'First Name'
                    ]
                ]
                )
//            ->add('initials')
//            ->add('serverdate')
//            ->add('campaignnr')
//            ->add('imisnr')
//            ->add('campaign')
//            ->add('birthdate')
//            ->add('nationality')
//            ->add('passengertype',
//                ChoiceType::class,
//                [
//                    'choices' => [
//                        'Scientist' => 'Scientist',
//                        'Diver' => 'Diver',
//                        'Passenger' => 'Passenger',
//                        'Student' => 'Student',
//                        'Volunteer' => 'Volunteer'
//                    ]
//                ]
//                )

//            ->add('expid')
//            ->add('investigatornr')
//            ->add('tripnr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tripinvestigators::class,
        ]);
    }
}
