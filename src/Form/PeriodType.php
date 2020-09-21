<?php

namespace App\Form;

use App\Entity\Periods;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class PeriodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startdate',
                DateType::class,
                [
                    'widget'=>'single_text',
                    'label'=>'Start Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd',
                        'class' => 'js-datepicker'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Date()
                    ]
                ]
            )
            ->add(
                'enddate',
                DateType::class,
                [
                    'widget'=>'single_text',
                    'label'=>'End Date',
                    'required' => true,
                    'html5'=> false,
                    'format'=> 'yyyy-MM-dd',
                    'attr'=>[
                        'placeholder' => 'YYYY-mm-dd',
                        'class' => 'js-datepicker'
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Date(),
                        new GreaterThanOrEqual([
                            'propertyPath' =>'parent.all[startdate].data'
                        ])
                    ]
                ]
                )
            ->add('short',
                ChoiceType::class,[
                    'label' => 'Category',
                    'choices' => [
                        'Multi-day trip' => 'Multi-day trip',
                        'WEEKEND - HOLIDAY' => 'WEEKEND - HOLIDAY',
                        'Maintenance MOB/DEMOB' =>'Maintenance MOB/DEMOB'
                    ]
                ])
//            ->add('description')
//            ->add('colorcode')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Periods::class,
        ]);
    }
}
