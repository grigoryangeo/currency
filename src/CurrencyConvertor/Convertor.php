<?php

namespace App\CurrencyConvertor;

use App\CurrencyLoader\ProviderInterface;
use App\Model\ConvertorRequest;

class Convertor
{
    /** @var  ProviderInterface */
    protected $currencyProvider;

    public function __construct(ProviderInterface $currencyProvider)
    {
        $this->currencyProvider = $currencyProvider;
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

        $fromValueInBase = $fromValue * $value;

        return round($fromValueInBase / $toValue, 4);
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
