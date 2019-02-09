<?php

namespace App\CurrencyLoader;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

class CbrProvider extends AbstractImporter implements ProviderInterface
{
    protected const BASE_CURRENCY = "RUB";

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
            $code    = (string) $xmlItem->CharCode;
            $value   = (float) $xmlItem->Value;
            $name    = (string) $xmlItem->Name;
            $nominal = (int) $xmlItem->Nominal;

            if ($nominal > 1) {
                $value = round($value / $nominal, 4);
            }
            $value        = 1 / $value;
            $item         = new CurrencyDto($code, $this->currencySource, $name, $value);
            $items[$code] = $item;
        }

        if (!count($items)) {
            throw new \Exception('Not has items');
        }

        $items[self::BASE_CURRENCY] =
            new CurrencyDto(self::BASE_CURRENCY, $this->currencySource, self::BASE_CURRENCY, 1);

        return $items;
    }
}
