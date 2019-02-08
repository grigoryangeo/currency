<?php

namespace App\Form;

use App\Form\DataTransformer\CurrencyCodeTransformer;
use App\Form\DataTransformer\IssueToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvertCodeType extends AbstractType
{
    private $transformer;

    public function __construct(CurrencyCodeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'invalid_message' => 'Currency does not exist',
            ]
        );
    }

    public function getParent()
    {
        return TextType::class;
    }
}
