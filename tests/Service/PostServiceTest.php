<?php
namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepositoryInterface;
use App\Service\PostService;
use Symfony\Component\EventDispatcher\EventDispatcher;

use App\Event\PostEvent;
use function foo\func;

class PostServiceTest extends TestCase
{

    public function testPost()
    {
        $postId = 1;
        $postSubject = 'First titles test';
        $postSlug = 'first-titles-test';

        $date = new \DateTime('now');
        $post = new Post;

        $author = new User;
        $author->setEmail('tata@gmail.com');
        $author->setLastname("my lname");
        $author->setFirstname("John");
        $author->setUsername('ibabou');

        $post->setId($postId);
        $post->setTitle($postSubject);
        $post->setSlug($postSlug);
        $post->setAuthor($author);
        $post->setPublishedAt((new \DateTime()));

        //Mock

        $postRepository = $this->createMock(PostRepositoryInterface::class);
        $postRepository->expects($this->any())
            ->method('findById')
            ->willReturn($post);

        $postService = new PostService($postRepository, new EventDispatcher());

        $post = $postService->getPostById($postId);

        $this->assertEquals($postId, $post->getId());
        $this->assertEquals($postSubject, $post->getTitle());
    }

    public function testCreation()
    {
        $postId = 1;
        $postSubject = 'First titles test version create';
        $postSlug = 'first-titles-test';

        $date = new \DateTime('now');
        $post = new Post;

        $author = new User;
        $author->setEmail('titi@gmail.com');
        $author->setLastname("my titi ");
        $author->setFirstname("John");
        $author->setUsername('titibandi');

        $post->setId($postId);
        $post->setTitle($postSubject);
        $post->setSlug($postSlug);
        $post->setAuthor($author);
        $post->setPublishedAt((new \DateTime()));

        $postRepository = $this->createMock(PostRepositoryInterface::class);

        $postRepository->expects($this->once())
            ->method('save')
            ->willReturn($post);

        $postService = new PostService($postRepository, new EventDispatcher());
        $postService->create($post);

        $this->assertEquals($postId, $post->getId());
    }

         /**
     * @test
     * @testdox  description
     */
    public function testCreateEvent()
    {
        $postId = 1;
        $postSubject = 'First titles test version create events dispatcher';
        $postSlug = 'first-titles-test-events-dispatcher';

        $date = new \DateTime('now');
        $post = new Post;

        $author = new User;
        $author->setEmail('titi@gmail.com');
        $author->setLastname("my titi ");
        $author->setFirstname("John");
        $author->setUsername('titibandi');

        $post->setId($postId);
        $post->setTitle($postSubject);
        $post->setSlug($postSlug);
        $post->setAuthor($author);
        $post->setPublishedAt((new \DateTime()));

        $postRepository = $this->createMock(PostRepositoryInterface::class);

        $postRepository->method('save');

        $dispatcher = new EventDispatcher();

        $dispatchedEvent = null;

        $dispatcher->addListener(PostEvent::CREATED, function ($event) use (&$dispatchedEvent){
            $dispatchedEvent = $event;
        });

        $postService = new PostService($postRepository, $dispatcher);
        $postService->create($post);

        $this->assertEquals( new PostEvent($post), $dispatchedEvent);
        $this->assertEquals($post, $dispatchedEvent->getPost());

    }
}
