<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectRepository;

class UserTest extends WebTestCase
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
    private $userRepository;

    protected function setUp()
    {
        $this->em = $this->getManagerRegistry();
        $this->userRepository = $this->em->getRepository(User::class);
    }

    public function testPublicBlogPost()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
            'This test is not available for testPageIsSuccessful.'
          );
        }
        $user = $this->userRepository->findOneBy([
            'username' => 'ibou888',
        ]);
        $this->assertSame('iboudiallo84@gmail.com', $user->getEmail(), 'Bad email');
    }

    protected function tearDown()
    {
        $this->em = null;
    }
}
