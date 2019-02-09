<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConvertorControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/currency/convert?from=test1&to=test1&value=1');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}