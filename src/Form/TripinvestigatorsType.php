<?php

namespace App\Form;

use App\Entity\Investigators;
use App\Entity\Tripinvestigators;
use App\Form\DataTransformer\InvestigatorToNumberTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripinvestigatorsType extends AbstractType
{

    private $transformer;

    public function __construct( InvestigatorToNumberTransformer $transformer )
    {
        $this->transformer=$transformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//           ->add('shortname')
            ->add('surname',
                TextType::class,
                [
                    'label' => 'Surname',
                    'attr' => [
                        'placeholder' => 'Surname'
                    ]

                ]
                )
            ->add('firstname',
                TextType::class,
                [
                    'label' => 'First Name',
                    'attr' => [
                        'placeholder' => 'First Name'
                    ]
                ]
                )
            ->add('fullname',
                TextType::class,
                [
                    'label'=>'Investigator',
                    'invalid_message' => 'That is not a valid investigator',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'type & choose...'
                    ]
                ]
                )

            ->add('investigatornr', HiddenType::class)


            ->add('campaign', TextType::class, [
                'label' => 'Campaign',
                'required' => false
            ])

            ->add('campaignnr', HiddenType::class)
            
        ;

        $builder->get('investigatornr')
            ->addModelTransformer($this->transformer);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tripinvestigators::class,
            'error_bubbling' => true
        ]);
    }
}
