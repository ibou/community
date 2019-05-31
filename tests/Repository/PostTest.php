<?php

namespace App\Tests\Repository;

use App\Entity\Post;
use App\Tests\FixtureAwareTestCase;
use App\Repository\PostRepository;
use App\DataFixtures\PostFixtures;
use App\Entity\User;

/**
 * @group  reposbdd
 */
class PostTest extends FixtureAwareTestCase
{

    use HelperTraitTest;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Undocumented variable.
     *
     * @var PostRepository
     */
    private $postRepository;

    protected function setUp()
    {
        parent::setUp();
        // $this->addFixture(new PostFixtures());
        // $this->executeFixtures();

        $kernel = static::bootKernel();

        $this->entityManager = $this->getManagerRegistry();

        $this->postRepository = $this->entityManager->getRepository(Post::class);
    }

    public function testFindById()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
                'This test is not available for testPageIsSuccessful.'
            );
        }
        $postId = 1;
        $postSubject = 'First titles test';
        $post = $this->postRepository->findById($postId);

        $this->assertEquals("{$postId}", $post->getId());
        $this->assertEquals("{$postSubject}", $post->getTitle());
    }

    public function testSave()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
                'This test is not available for testPageIsSuccessful.'
            );
        }
        $post = new Post;
        $author = new User;
        $time = Date("Ymis");
        $author->setEmail("user_{$time}@gmail.com");
        $author->setLastname("User {$time}");
        $author->setUsername("user_{$time}");
        // $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $author->setPassword('$argon2i$v=19$m=1024,t=2,p=2$SFlhdzFDRklkZy9mRXhUOA$vZcDojSl6yZBjGfWOdSpGW6nd1AGflx1nDNnt+ySvbU');

        $post->setTitle('Second titles test');
        $post->setSlug('second-titles-test');
        $post->setContent('A second A content <b>un texte en gras </b>. Ceci est un est exemple de texte');
        $post->setAuthor($author);
        $post->setPublishedAt((new \DateTime()));
        $this->postRepository->save($post);
        $this->entityManager->flush();
        $this->assertNotNull($post->getId());
        $this->postRepository->remove($post);
        $this->entityManager->flush();
    }

    protected function tearDown()
    {
        $this->postRepository = null;
    }
}
