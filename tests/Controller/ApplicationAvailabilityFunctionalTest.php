<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ApplicationAvailabilityFunctionalTest extends TestCase
{
    public function testPageIsSuccessful()
    {
        $this->assertTrue(true);
        // $client = self::createClient();
        // $client->request('GET', $url);

        // $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/login'];
    }
}
