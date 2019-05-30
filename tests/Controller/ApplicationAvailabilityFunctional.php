<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{


    public function setUp()
    {
        $client = self::createClient();
        $client->followRedirects(true);
    }

    /**
     * @dataProvider urlProvider
     */
    // public function testPageIsSuccessful($url)
    // {
    // 	$client = static::createClient();
    //     if (!extension_loaded('pdo_mysql')) {
    //         $this->markTestSkipped(
    //         'This test is not available for testPageIsSuccessful.'
    //       );
    //     }
    //     $client->request('GET', $url);
    //      $this->assertSame(
    //         Response::HTTP_OK,
    //         $client->getResponse()->getStatusCode(),
    //         sprintf('The %s public URL loads correctly.', $url)
    //     );
    // }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/login'];
        yield ['/register'];
        yield ['/contact-us'];
        yield ['/login/reset-password'];
        yield ['/posts/page/1'];
    }

    // public function testViewPostNotFound(){
    // 	$client = static::createClient();
    //     if (!extension_loaded('pdo_mysql')) {
    //         $this->markTestSkipped(
    //         'This test is not available for testPageIsSuccessful.'
    //       );
    //     }
    //     $url = '/posts/article/first-tifjlkeahfeaf-tles-test';
    //     $client->request('GET', $url);
    //     $this->assertSame(
    //         Response::HTTP_NOT_FOUND,
    //         $client->getResponse()->getStatusCode(),
    //         sprintf('The %s public URL loads correctly.', $url)
    //     );

    // }


    public function testShowPost()
    {
        $client = static::createClient();

        $client->request('GET', '/posts/page/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
