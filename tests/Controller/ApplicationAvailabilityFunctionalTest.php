<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful(string $url)
    {
        $this->markTestSkipped(
            'This test is not available for testPageIsSuccessful.'
          );
        $this->client->request('GET', $url);
        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode(),
            sprintf('The %s public URL loads correctly.', $url)
        );
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/login'];
        yield ['/register'];
        yield ['/contact-us'];
        yield ['/resetting/request'];
    }
}
