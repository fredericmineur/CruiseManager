<?php

namespace App\Form;

use App\Entity\Investigators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestigatorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('surname',
                TextType::class, [
                    'label' => 'Surname',
                    'attr' => [
                        'placeholder' => 'Surname'
                    ]
                ])
            ->add('firstname',
                TextType::class,[
                    'label' => 'First name',
                    'attr' => [
                        'placeholder' => 'First name'
                    ]
                ])
            ->add('imisnr',
                TextType::class,[
                    'label' => 'IMIS number',
                    'attr' => [
                        'placeholder' => 'e.g. 12345'
                    ]]
            )
//            ->add('passengertype',
//                TextType::class,[
//                    'label' => 'Passenger type',
//                    'attr' => [
//                        'placeholder' => 'e.g. Scientist'
//                    ]
//                ])

            ->add('passengertype',
                ChoiceType::class,[
                    'label' => 'Passenger type',
//                    'attr' => [
//                        'placeholder' => 'e.g. Scientist'
//                    ]
                'choices' => [
                    'Diver' => 'Diver',
                    'Passenger' => 'Passenger',
                    'Scientist' => 'Scientist',
                    'Student' => 'Student',
                    'Volunteer' => 'Volunteer'
                ]

                ])


//            ->add('shortname',
//                TextType::class, [
//                    'label' => 'Short name',
//                    'attr' => [
//                        'placeholder' => 'Short Name'
//                    ]
//                ])
//            ->add('initials',
//                TextType::class,[
//                    'label' => 'Initials',
//                    'attr' => [
//                        'placeholder' => 'Initials'
//                    ]
//                ])
//            ->add('memo',
//                TextareaType::class,[
//                    'label' => 'Memo',
//                    'attr' => [
//                        'placeholder' => 'Memo text'
//                ])


//            ->add('birthdate',[
//
//            ])
//            ->add('nationality')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Investigators::class,
        ]);
    }
}
