<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Entity\Cruise;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campaign',
                TextType::class,
                $this->getConfiguration('Name of the campaign', 'Campaign')
                )
            ->add('memo',
                TextareaType::class,
                $this->getConfiguration('Memo', 'Memo')
            )
            ->add('imisprojectnr',
                TextType::class,
                $this->getConfiguration('IMIS project number', 'IMIS project number')
            )
//            ->add('cruise',
//                CollectionType::class, [
//                    'entry_type' => CruiseType::class
//                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
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
