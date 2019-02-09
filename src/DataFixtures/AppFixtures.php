<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setCode("test1");
        $currency->setName("test1");
        $currency->setValue(1.1);
        $currency->setSource("test1");
        $currency->setActive(true);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode("test2");
        $currency->setName("test2");
        $currency->setValue(2.1);
        $currency->setSource("test1");
        $currency->setActive(true);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode("test3");
        $currency->setName("test3");
        $currency->setSource("test2");
        $currency->setValue(3.1);
        $currency->setActive(true);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode("test4");
        $currency->setName("test4");
        $currency->setSource("test2");
        $currency->setValue(4.1);
        $currency->setActive(false);
        $manager->persist($currency);

        $manager->flush();
    }
}
