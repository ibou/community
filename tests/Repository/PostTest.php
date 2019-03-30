<?php

namespace App\Tests\Repository;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostTest extends WebTestCase
{
    use HelperTraitTest;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Undocumented variable.
     *
     * @var ObjectRepository
     */
    private $postRepository;

    protected function setUp()
    {
        $this->em = $this->getManagerRegistry();
        $this->postRepository = $this->em->getRepository(Post::class);
    }

    public function testPublicBlogPost()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
            'This test is not available for testPageIsSuccessful.'
          );
        }
        $id = 1;
        $post = $this->postRepository->findBy(['id' => $id]);
        $this->assertCount(1, $post, "La valeur du post {$id} est vide ");
    }

    protected function tearDown()
    {
        $this->em = null;
    }
}
