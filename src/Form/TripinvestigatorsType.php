<?php

namespace App\Form;

use App\Entity\Tripinvestigators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripinvestigatorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortname')
            ->add('surname')
            ->add('firstname')
            ->add('initials')
            ->add('serverdate')
            ->add('campaignnr')
            ->add('imisnr')
            ->add('campaign')
            ->add('birthdate')
            ->add('nationality')
            ->add('passengertype')
            ->add('expid')
            ->add('investigatornr')
            ->add('tripnr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tripinvestigators::class,
        ]);
    }
}
