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

    public function FindByTitle()
    {
        $post = $this->postRepository->findOneBy(['slug' => 'first-titles-test']);
        $this->assertEquals(1, $post->getId(), "le titre est mauvais !!");
    }
    public function testFindById()
    {
        $postId = 1;
        $postSubject = 'First titles test';
        $post = $this->postRepository->findById($postId);

        $this->assertEquals("{$postId}", $post->getId());
        $this->assertEquals("{$postSubject}", $post->getTitle());


        $post = $this->postRepository->findOneByFields([
            'enabled' => '1',
        ]);
        // $this->assertEquals(1, $post->getId(), "le titre est mauvais !!");
    }

    public function testSave()
    {
        $post = new Post;
        $author = new User;
        $author->setEmail('toto@gmail.com');
        $author->setLastname("my lname");
        $author->setUsername('usbdubdsdsd');
        // $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $author->setPassword('flnflknaeflkneaf');

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
