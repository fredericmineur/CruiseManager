<?php

namespace App\Form;

use App\Entity\Investigators;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvestigatorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortname',
                TextType::class, [
                    'label' => 'Short name',
                    'attr' => [
                        'placeholder' => 'Short Name'
                    ]
                ])
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
//            ->add('initials')
//            ->add('memo')
//            ->add('imisnr')
//            ->add('birthdate')
//            ->add('nationality')
//            ->add('passengertype')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Investigators::class,
        ]);
    }
}
