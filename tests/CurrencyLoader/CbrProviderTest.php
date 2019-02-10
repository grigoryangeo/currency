<?php

namespace App\Tests\CurrencyConvertor;

use App\CurrencyLoader\CbrProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CbrProviderTest extends KernelTestCase
{
    protected $cbrProvider;

    protected $em;

    public function testCurrencySource()
    {
        $this->assertEquals("CBR", $this->cbrProvider->getCurrencySource());
    }

    public function testBadUrl()
    {
        try {
            $cbrProvider = new CbrProvider($this->em, "not url");
        } catch (\Exception $e) {
            $this->assertEquals("Not valid sourceUrl", $e->getMessage());
        }
    }

    public function testGetAvailableCurrencies()
    {
        $currencies = $this->cbrProvider->getAvailableCurrencies();
        $this->assertCount(0, $currencies);

        $providerMock = $this->getMockBuilder(CbrProvider::class)
            ->setConstructorArgs([$this->em, "http://localhost"])
            ->setMethods(['getCurrencySource'])
            ->getMock()
        ;

        $providerMock->expects($this->any())
            ->method('getCurrencySource')
            ->will($this->returnValue("test2"))
        ;

        $currencies = $providerMock->getAvailableCurrencies();
        $this->assertCount(2, $currencies);

        $providerMock = $this->getMockBuilder(CbrProvider::class)
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
    }

    protected function setUp()
    {
        static::bootKernel();

        $this->em          = static::$kernel->getContainer()->get("doctrine.orm.entity_manager");
        $this->cbrProvider = new CbrProvider($this->em, "http://localhost");
    }

    protected function tearDown()
    {
        $this->cbrProvider = null;
        $this->em          = null;
    }
}