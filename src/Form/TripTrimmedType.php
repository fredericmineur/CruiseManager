<?php

namespace App\Form;

use App\Entity\Cruise;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripTrimmedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdate',
                DateTimeType::class,[
                    'label' => 'Start Date',
                    'attr' => [
                        'placeholder' => 'd/m/Y'
                    ]
                ])

            ->add('enddate',
                DateTimeType::class, [
                    'label' =>'End Date',
                    'attr' => [
                        'placeholder' => 'd/m/Y'
                    ]
                ])

            ->add('startpoint',
                TextType::class, [
                    'label' => 'Start point',
                    'attr' => [
                        'placeholder' => 'Call port'
                    ]
                ])

            ->add('endpoint',
                TextType::class, [
                    'label' => 'End point',
                    'attr' => [
                        'placeholder' => 'Call port'
                    ]
                ])

            ->add('destinationarea',
                TextType::class, [
                    'label' => 'Destination area',
                    'attr' => [
                        'placeholder' => 'Destination area'
                    ]
                ])

            ->add('memo',
                TextareaType::class, [
                    'label' => 'Notes',
                    'attr' => [
                        'placeholder' => 'Memo'
                    ]
                ])

            ->add('status', //DONE or CANCELLED
                TextType::class,[
                    'label' => 'Status',
                    'attr' => [
                        'placeholder' => 'Status'
                    ]
                ])

            ->add('logtext',
                TextareaType::class, [
                    'label' => 'Log Text',
                    'attr' => [
                        'placeholder' => 'Log text'
                    ]
                ])

            ->add('ship',
                TextType::class, [
                    'label' =>'Ship',
                    'attr' => [
                        'placeholder' => 'Ship'
                    ]
                ])

            ->add('insync', //bit
                CheckboxType::class, [
                    'label' => 'Insync',
                    'required' => false
                ]
                )
            ->add('gpsstart',
                DateTimeType::class,[
                    'label' => 'GPS start time',
                    'attr' => [
                        'placeholder' => 'GPS start time'
                    ]
                ])

            ->add('gpsstop',
                DateTimeType::class, [
                    'label' => 'GPS stop time',
                    'attr' => [
                        'placeholder' => 'GPS stop time'
                    ]
                ])

            ->add('traveldistance',
                NumberType::class, [
                    'label' => 'Tavel distance',
                    'attr' => [
                        'placeholder' => ''
                    ]
                ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }


}
