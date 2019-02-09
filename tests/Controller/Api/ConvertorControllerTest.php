<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConvertorControllerTest extends WebTestCase
{
    public function testRequestWithoutParams()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/currency/convert');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());

        $this->assertEquals(false, $response->success);
        $this->assertCount(3, $response->errors);

        $this->assertEquals("from", $response->errors[0]->property_path);
        $this->assertEquals("This value should not be blank.", $response->errors[0]->message);

        $this->assertEquals("to", $response->errors[1]->property_path);
        $this->assertEquals("This value should not be blank.", $response->errors[1]->message);

        $this->assertEquals("value", $response->errors[2]->property_path);
        $this->assertEquals("This value should not be blank.", $response->errors[2]->message);
    }

    public function testBadRequest()
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/currency/convert?from=NOT_EXIST&to=NOT_EXIST&value=BAD_VALUE');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());

        $this->assertEquals(false, $response->success);
        $this->assertCount(3, $response->errors);

        $this->assertEquals("Currency does not exist", $response->errors[0]);
        $this->assertEquals("Currency does not exist", $response->errors[1]);
        $this->assertEquals("This value is not valid.", $response->errors[2]);
    }
}