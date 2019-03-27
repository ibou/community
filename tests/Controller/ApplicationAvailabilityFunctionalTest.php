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
        $this->assertTrue(true);
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
    }
    public function testPublicBlogPost()
    {
        // the service container is always available via the test client
        $blogPost = $this->client->getContainer()->get('doctrine')->getRepository(Post::class)->find(104);

        $this->client->request('GET', sprintf('/posts/article/%s', $blogPost->getSlug()));
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}