<?php

namespace App\Form;

use App\CurrencyConvertor\Convertor;
use App\Model\ConvertorRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConvertType extends AbstractType
{
    /** @var  Convertor */
    private $convertor;

    public function __construct(Convertor $convertor)
    {
        $this->convertor = $convertor;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currencies      = $this->convertor->getAvailableCurrencies();
        $currenciesCodes = [];
        foreach ($currencies as $currency) {
            $currenciesCodes[$currency->getCode()] = $currency->getCode();
        }

        $builder
            ->setMethod('GET')
            ->add(
                'from',
                ChoiceType::class,
                [
                    'choices' => $currenciesCodes,
                ]
            )
            ->add(
                'to',
                ChoiceType::class,
                [
                    'choices' => $currenciesCodes,
                ]
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