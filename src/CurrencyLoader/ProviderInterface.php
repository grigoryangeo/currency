<?php

namespace App\CurrencyLoader;

interface ProviderInterface
{
    /**
     * Format data from source format to standart
     *
     * @return array
     */
    public function formatData(\SimpleXMLElement $data): array;
}
