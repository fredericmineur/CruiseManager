<?php

namespace App\Form;

use App\Entity\Investigators;
use PHP_CodeSniffer\Generators\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                    'required' => false,
                    'attr' => [
                        'readonly' => true
                    ]
                ]
            )

            ->add('passengertype',
                ChoiceType::class,[
                    'label' => 'Passenger type',
//                    'attr' => [
//                        'placeholder' => 'e.g. Scientist'
//                    ]
                'choices' => [
                    'Scientist' => 'Scientist',
                    'Diver' => 'Diver',
                    'Passenger' => 'Passenger',
                    'Student' => 'Student',
                    'Volunteer' => 'Volunteer'
                ]

                ])

            ->add('fullName',
                ChoiceType::class, [
                    'choices' => [
                        '' => 1,
                    ],
                    'mapped' => false,
                    'required' => false,
                    'empty_data'=>[],
                    'label' => 'Full name (for IMIS search)',
                ]
            )
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                $data = $event->getData();
                $data['fullName'] =1;
                $event->setData($data);
            }
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Investigators::class,
        ]);
    }
}
