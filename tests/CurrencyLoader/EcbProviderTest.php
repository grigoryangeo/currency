<?php

namespace App\Tests\CurrencyConvertor;

use App\CurrencyLoader\EcbProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EcbProviderTest extends KernelTestCase
{
    protected $ecbProvider;

    protected $em;

    public function testCurrencySource()
    {
        $this->assertEquals("ECB", $this->ecbProvider->getCurrencySource());
    }

    public function testBadUrl()
    {
        try {
            $ecbProvider = new EcbProvider($this->em, "not url");
        } catch (\Exception $e) {
            $this->assertEquals("Not valid sourceUrl", $e->getMessage());
        }
    }

    public function testGetAvailableCurrencies()
    {
        $currencies = $this->ecbProvider->getAvailableCurrencies();
        $this->assertCount(0, $currencies);

        $providerMock = $this->getMockBuilder(EcbProvider::class)
            ->setConstructorArgs([$this->em, "http://localhost"])
            ->setMethods(['getCurrencySource'])
            ->getMock()
        ;

        $providerMock->expects($this->any())
            ->method('getCurrencySource')
            ->will($this->returnValue("test1"))
        ;

        $currencies = $providerMock->getAvailableCurrencies();
        $this->assertCount(2, $currencies);

        $providerMock->expects($this->any())
            ->method('getCurrencySource')
            ->will($this->returnValue("test2"))
        ;

        $currencies = $providerMock->getAvailableCurrencies();
        $this->assertCount(2, $currencies);
    }

    protected function setUp()
    {
        static::bootKernel();

        $this->em          = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");
        $this->ecbProvider = new EcbProvider($this->em, "http://localhost");
    }

    protected function tearDown()
    {
        $this->ecbProvider = null;
        $this->em          = null;
    }
}