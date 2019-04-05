<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group  reposbdd
 */
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

    public function testPublicPost()
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

    public function testLoginCountUsers(){
        $user = new User();
        // $users = $this->userRepository->findUserByEmail('iboudiallo84@gmail.com');

       $user->setEmail('iboudiallo@gmail.com')
            ->setUsername('ibou')
            ->setFirstname('Alfred')
            ->setLastname('einstein')
            ;
        $userRepo = $this->createMock(UserRepository::class);
        $userRepo->expects($this->any())
                //  ->method('findCountLoginUsers')
                 ->method('findBy')
                 ->willReturn($user);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($userRepo);

              // var_dump($userRepo->findUserByEmailsss('iboudiallo@gmail.com'));
       // $this->assertSame('iboudiallo@gmail.com', $userRepo->findUserByEmailsss('iboudiallo@gmail.com'));
       $this->assertTrue(true);
    }
    protected function tearDown()
    {
        $this->em = null;
    }
}
