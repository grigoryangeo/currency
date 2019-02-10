<?php

namespace App\Tests\CurrencyConvertor;

use App\CurrencyConvertor\Convertor;
use App\CurrencyLoader\EcbProvider;
use App\Entity\Currency;
use App\Model\ConvertorRequest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConvertorTest extends KernelTestCase
{
    public function testConvert()
    {
        $ecbProvider = new EcbProvider($this->em, "http://localhost");
        $convertor   = new Convertor([$ecbProvider], "ECB");

        $currencyFrom = new Currency();
        $currencyFrom->setValue(1);

        $currencyTo = new Currency();
        $currencyTo->setValue(2);

        $convertorRequest = new ConvertorRequest();
        $convertorRequest->setFrom($currencyFrom);
        $convertorRequest->setTo($currencyTo);
        $convertorRequest->setValue(2);

        $convertedValue = $convertor->convert($convertorRequest);
        $this->assertEquals(4, $convertedValue);

        $convertorRequest->setTo($currencyFrom);
        $convertedValue = $convertor->convert($convertorRequest);
        $this->assertEquals(2, $convertedValue);

        $convertorRequest->setValue(1.33);
        $convertedValue = $convertor->convert($convertorRequest);
        $this->assertEquals(1.33, $convertedValue);

        $convertorRequest->setTo($currencyTo);
        $convertorRequest->setValue(1.5);
        $convertedValue = $convertor->convert($convertorRequest);
        $this->assertEquals(3, $convertedValue);

        $currencyFrom->setValue(0);
        try {
            $convertedValue = $convertor->convert($convertorRequest);
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), "Division by zero");
        }
    }

    protected function setUp()
    {
        static::bootKernel();

        $this->em = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");
    }

    protected function tearDown()
    {
        $this->em = null;
    }
}