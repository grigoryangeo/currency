<?php

namespace App\Form;

use App\Model\ConvertorRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add(
                'from',
                ConvertCodeType::class
            )
            ->add(
                'to',
                ConvertCodeType::class
            )
            ->add(
                'value',
                NumberType::class,
                [
                    'required' => true,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'      => ConvertorRequest::class,
                'csrf_protection' => false,
            ]
        );
    }
}