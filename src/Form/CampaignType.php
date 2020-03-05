<?php

namespace App\Form;

use App\Entity\Campaign;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignType extends AbstractType
{




    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campaign',
                TextType::class, [
                    'label' => 'Name of the campaign',
                    'required'=>true,
                    'attr' => [
                        'placeholder' => 'Campaign'
                    ]
                ])

            ->add('imistitle',
                ChoiceType::class, [
                    'choices' => [
                        '' => 1,
                    ],
                    'mapped' => false,
                    'required' => false,
                    'empty_data'=>[]
                    ]
                )

            ->add('memo',
                TextareaType::class, [
                    'label' => 'Memo',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Memo'
                    ]
                ]
            )
            ->add('imisprojectnr',
                TextType::class, [
                    'label' => 'IMIS project number',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'IMIS project number',
                         'readonly' => true
                    ]
                ]
            );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                $data = $event->getData();
                $data['imistitle'] =1;
                $event->setData($data);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
    }

}
