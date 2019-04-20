<?php

namespace App\Tests\Repository;

use App\Entity\Post;  
use App\Tests\FixtureAwareTestCase;
use App\Repository\PostRepository;
use App\DataFixtures\PostFixtures;

/**
 * @group  reposbdd
 */
class PostRepositoryTest extends FixtureAwareTestCase
{
     
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
        $this->addFixture(new PostFixtures());
        $this->executeFixtures();

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        // $this->postRepository = new PostRepository($this->entityManager);
        $this->postRepository = $kernel->getContainer()->get('test.App\Repository\PostRepository');

    }

    public function testFindById()
    {
        $postId = 1;
        $postSubject = 'My first title';
        $post = $this->postRepository->findById(1);
 
    }

 

    protected function tearDown()
    {
        $this->em = null;
    }
}
