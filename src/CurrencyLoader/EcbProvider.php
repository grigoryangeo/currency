<?php

namespace App\CurrencyLoader;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

class EcbProvider extends AbstractImporter implements ProviderInterface
{
    public function __construct(EntityManagerInterface $em, string $sourceUrl)
    {
        $this->currencySource = Currency::SOURCES_ECB;
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
        $xmlItems = $xmlItems->Cube->Cube->Cube;
        $items    = [];
        foreach ($xmlItems as $xmlItem) {
            $code  = "";
            $value = 0;
            foreach ($xmlItem->attributes() as $codeAttr => $value) {
                if ($codeAttr == "currency") {
                    $code = (string) $value;
                }
                if ($codeAttr == "rate") {
                    $value = (float) $value;
                }
            }

            $item         = new CurrencyDto($code, $this->currencySource, $code, $value);
            $items[$code] = $item;
        }

        if (!count($items)) {
            throw new \Exception('Not has items');
        }

        return $items;
    }
}
