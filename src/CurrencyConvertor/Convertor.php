<?php

namespace App\CurrencyConvertor;

use App\CurrencyLoader\ProviderInterface;
use App\Model\ConvertorRequest;

class Convertor
{
    /** @var  ProviderInterface */
    protected $currencyProvider;

    public function __construct(iterable $currencyProviders, string $activeSource)
    {
        foreach($currencyProviders as $currencyProvider) {
            if($currencyProvider instanceof ProviderInterface) {

                if($currencyProvider->getCurrencySource() == $activeSource) {
                    $this->currencyProvider = $currencyProvider;
                }
            }
        }

        if(!$this->currencyProvider) {
            throw new \Exception('Not one currency provider selected');
        }
    }

    /**
     * Convert
     *
     * @param ConvertorRequest $convertorRequest
     *
     * @return float
     */
    public function convert(ConvertorRequest $convertorRequest): float
    {
        $from = $convertorRequest->getFrom();
        $to   = $convertorRequest->getTo();

        $fromValue = $from->getValue();
        $toValue   = $to->getValue();
        $value     = $convertorRequest->getValue();

        $convertedValue = ($value * $toValue) / $fromValue;

        return round($convertedValue, 4);
    }

    /**
     * Get currency source
     *
     * @access public
     * @return string
     */
    public function getCurrencySource(): string
    {
        return $this->currencyProvider->getCurrencySource();
    }
}
