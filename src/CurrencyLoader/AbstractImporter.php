<?php

namespace App\CurrencyLoader;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractImporter
{
    const TIMEOUT = 30;

    /** @var  Client */
    protected $client;

    /** @var  OutputInterface */
    protected $output;

    /** @var  string */
    protected $sourceUrl;

    /** @var  EntityManagerInterface */
    protected $em;

    /** @var  Serializer */
    protected $serializer;

    /** @var  string */
    protected $currencySource;

    public function __construct(EntityManagerInterface $em, string $sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
        $this->em        = $em;

        if (!$this->currencySource) {
            throw new \Exception('CurrencyCode is required sourceUrl');
        }

        if (!filter_var($this->sourceUrl, FILTER_VALIDATE_URL)) {
            throw new \Exception('Not valid sourceUrl');
        }

        $encoders    = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * import
     *
     * @return void
     */
    public function import(?OutputInterface $output): void
    {
        if ($output) {
            $this->output = $output;
        }
        $items = $this->downloadData();
        $this->insertData($items);
    }

    /**
     * Download data from source
     *
     * @throws \Exception invalid response
     * @return null|array
     */
    public function downloadData():?array
    {
        $requestUrl = $this->sourceUrl;

        $client   = new Client();
        $response = $client->request(
            'GET',
            $requestUrl,
            [
                'headers' => ['Accept' => 'application/xml'],
                'timeout' => self::TIMEOUT,
            ]
        )->getBody()->getContents()
        ;

        $responseXml = simplexml_load_string($response);
        if (!($responseXml instanceof \SimpleXMLElement)) {
            throw new \Exception('Not valid respose');
        }

        return $this->formatData($responseXml);
    }

    /**
     * Insert data
     *
     * @param array $items
     *
     * @return void
     */
    public function insertData(array $items): void
    {
        $currencies = $this->getAvailableCurrencies();
        $notExists  = $items;

        $inserted    = 0;
        $updated     = 0;
        $deactivated = 0;
        foreach ($currencies as $currency) {
            $code = $currency->getCode();
            if (isset($items[$code])) {

                if (!($items[$code] instanceof CurrencyDto)) {
                    $this->toOutput("Not instanceof CurrencyDto", "comment");
                    continue;
                }
                unset($notExists[$code]);
                $currency->setValue($items[$code]->getValue());
                $currency->setActive(true);
                $updated++;
            } else {
                $currency->setActive(false);
                $deactivated++;
            }
        }

        foreach ($notExists as $code => $item) {
            if (!($item instanceof CurrencyDto)) {
                $this->toOutput("Not instanceof CurrencyDto", "comment");
                continue;
            }
            $jsonItem    = $this->serializer->serialize($item, 'json');
            $newCurrency = $this->serializer->deserialize($jsonItem, Currency::class, 'json');
            $this->em->persist($newCurrency);
            $inserted++;
        }

        $this->em->flush();
        $this->toOutput("Inserted: $inserted");
        $this->toOutput("Updated: $updated");
        $this->toOutput("Deactivated: $deactivated");
    }

    /**
     * Get available currency
     *
     * @return array
     */
    public function getAvailableCurrencies(): array
    {
        return $this->em->getRepository(Currency::class)->getAllBySource($this->currencySource);
    }

    /**
     * Send data to output
     *
     * @param string $msg
     * @param string $type
     *
     * @return void
     */
    public function toOutput(string $msg, string $type = "info"): void
    {
        if ($this->output) {
            $this->output->writeln("<$type>" . $msg . "</$type>");
        }
    }

    /**
     * Get currency source
     *
     * @return string
     */
    public function getCurrencySource(): string
    {
        return $this->currencySource;
    }
    
}
