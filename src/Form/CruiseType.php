<?php

namespace App\Form;

use App\Entity\Cruise;
use App\Entity\Investigators;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                DateTimeType::class,
                $this->getConfiguration('Start Date', 'd/m/Y')
            )
            ->add('enddate',
                DateTimeType::class,
                $this->getConfiguration('End Date', 'd/m/Y')
            )
            ->add('destination',
                TextType::class,
                $this->getConfiguration('Destination', 'Destination'))
            ->add('memo',
                TextareaType::class,
                $this->getConfiguration('Notes', 'Memo'))
            ->add('plancode',
                TextType::class,
                $this->getConfiguration('Plan code', 'xx-xxx'))
            ->add('ship',
                TextType::class,
                $this->getConfiguration('Ship', 'Name of the ship'))
            ->add('purpose',
                TextType::class,
                $this->getConfiguration('Purpose', 'Purpose'))
//            ->add('campaign')
            ->add('principalinvestigator',
                EntityType::class, [
                    'label' => 'Principal Investigator',
                    'class' => Investigators::class,
                    'multiple' => true,
//                    'expanded' => true,
                    'choice_label' => 'surname'

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cruise::class,
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
