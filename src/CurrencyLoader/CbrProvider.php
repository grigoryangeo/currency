<?php

namespace App\CurrencyLoader;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

class CbrProvider extends AbstractImporter implements ProviderInterface
{
    public function __construct(EntityManagerInterface $em, string $sourceUrl)
    {
        $this->currencySource = Currency::SOURCES_CBR;
        parent::__construct($em, $sourceUrl);
    }

    /**
     * format data
     *
     * @param \SimpleXMLElement $xmlItems
     *
     * @throws \Exception not has items
     * @return array
     */
    public function formatData(\SimpleXMLElement $xmlItems): array
    {
        $items = [];
        foreach ($xmlItems as $xmlItem) {
            $code         = (string) $xmlItem->CharCode;
            $value        = (float) $xmlItem->Value;
            $name         = (string) $xmlItem->Name;
            $item         = new CurrencyDto($code, $this->currencySource, $name, $value);
            $items[$code] = $item;
        }

        if (!count($items)) {
            throw new \Exception('Not has items');
        }

        return $items;
    }
}
