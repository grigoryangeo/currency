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
     * @access public
     *
     * @param ConvertorRequest $convertorRequest
     *
     * @return float
     */
    public function convert(ConvertorRequest $convertorRequest): float
    {
        $from = $convertorRequest->getFrom();
        $to   = $convertorRequest->getTo();

        return $convertorRequest->getValue();
    }

    /**
     * Get available currency
     *
     * @access public
     * @return array
     */
    public function getAvailableCurrencies(): array
    {
        return $this->currencyProvider->getAvailableCurrencies();
    }
}
