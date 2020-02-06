<?php

namespace App\Form;

use App\Entity\Campaign;
use App\Entity\Cruise;
use App\Form\DataTransformer\CampaignToNumberTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignType extends AbstractType
{

//    private $transformer;
//
//    /**
//     * CampaignType constructor.
//     * @param $transfomer
//     */
//    public function __construct(CampaignToNumberTransformer $transformer)
//    {
//        $this->transformer = $transformer;
//    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campaign',
                TextType::class, [
                    'label' => 'Name of the campaign',
                    'attr' => [
                        'placeholder' => 'Campaign'
                    ]
                ])
            ->add('memo',
                TextareaType::class, [
                    'label' => 'Memo',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Memo'
                    ]
                ]
            )
//            ->add('imisprojectnr',
//                TextType::class, [
//                    'label' => 'IMIS project number',
//                    'required' => false,
//                    'attr' => [
//                        'placeholder' => 'IMIS project number'
//                    ]
//                ]
//            )

            ->add('imisprojectnr',
                HiddenType::class
            )


//            ->add('campaignid',
//                HiddenType::class)

        ;

//        $builder->get('campaignid')
//            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
    }

}
