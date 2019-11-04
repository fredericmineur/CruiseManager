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

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdate',
                DateTimeType::class,
                $this->getConfiguration('Start Date', 'd/m/Y'))
            ->add('enddate',
                DateTimeType::class,
                $this->getConfiguration('End Date', 'd/m/Y'))
            ->add('startpoint',
                TextType::class,
                $this->getConfiguration('Start point', 'Call port'))
            ->add('endpoint',
                TextType::class,
                $this->getConfiguration('End point', 'Call port'))
            ->add('destinationarea',
                TextType::class,
                $this->getConfiguration('Destination area', 'Destination area'))
            ->add('memo',
                TextareaType::class,
                $this->getConfiguration('Notes', 'Memo'))
            ->add('status', //DONE or CANCELLED
                TextType::class,
                $this->getConfiguration('Status', 'Status'))
            ->add('logtext',
                TextareaType::class,
                $this->getConfiguration('Log Text', 'Log text')
                )
            ->add('ship',
                TextType::class,
                $this->getConfiguration('Ship', 'Ship'))
            ->add('insync', //bit
                CheckboxType::class, [
                    'label' => 'Insync',
                    'required' => false
                ]
                )
            ->add('gpsstart',
                DateTimeType::class,
                $this->getConfiguration('GPS start time', 'GPS start position')
                )
            ->add('gpsstop',
                DateTimeType::class,
                $this->getConfiguration('GPS stop time', 'GPS stop time'))
            ->add('traveldistance',
                NumberType::class,
                $this->getConfiguration('Tavel distance', '')
                )
//            ->add('leaveportdate',
//                DateTimeType::class
//                )
//            ->add('arriveportdate')

                //https://symfonycasts.com/screencast/collections/entity-type-checkboxes
                //https://symfonycasts.com/screencast/symfony-forms/entity-type
            ->add('cruiseid', EntityType::class,[
                'class' => Cruise::class,
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }

    public function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
}
