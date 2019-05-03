<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Post;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient( );
        $this->client->followRedirects(true);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful(string $url)
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
            'This test is not available for testPageIsSuccessful.'
          );
        }
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
        yield ['/login/reset-password'];
        yield ['/posts/page/1'];
    }

    public function testViewPostNotFound(){
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
            'This test is not available for testPageIsSuccessful.'
          );
        }
        $url = '/posts/article/first-tifjlkeahfeaf-tles-test';
        $this->client->request('GET', $url);
        $this->assertSame(
            Response::HTTP_NOT_FOUND,
            $this->client->getResponse()->getStatusCode(),
            sprintf('The %s public URL loads correctly.', $url)
        );

    }
}
