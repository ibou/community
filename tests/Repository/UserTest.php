<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group  reposbdd
 */
class UserTest extends KernelTestCase
{
    use HelperTraitTest;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Undocumented variable.
     *
     * @var ObjectRepository
     */
    private $userRepository;

    protected function setUp()
    {
        $this->entityManager = $this->getManagerRegistry();
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testUsernamePost()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
                'This test is not available for testPageIsSuccessful.'
            );
        }
        $user = $this->userRepository->findOneBy([
            'username' => 'testiboudiallo',
        ]);
        $this->assertSame('testiboudiallo@gmail.com', $user->getEmail(), 'Bad email');
    }

    public function testFindUserByEmailPost()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
                'This test is not available for testPageIsSuccessful.'
            );
        }
        $user = $this->userRepository->findUserByEmail('testiboudiallo@gmail.com');
        $this->assertSame('testiboudiallo@gmail.com', $user->getEmail(), 'Bad email');

        $user = new User();

        $user->setEmail('iboudiallo@gmail.com')
            ->setUsername('ibou')
            ->setFirstname('Alfred')
            ->setLastname('einstein');

        $userRepoMocked = $this->createMock(UserRepository::class);
        $userRepoMocked->expects($this->any())
            ->method('findUserByEmail')
            ->willReturn($user);

        $userRepos = $userRepoMocked->findUserByEmail('iboudiallo@gmail.com');

        $this->assertSame($user, $userRepos);
        $this->assertSame('iboudiallo@gmail.com', $userRepos->getEmail());
    }

     public function testLoginCountUsers()
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped(
                'This test is not available for testPageIsSuccessful.'
            );
        }
        $user = new User();

        $user->setEmail('iboudiallotata@gmail.com')
            ->setUsername('ibou')
            ->setFirstname('Alfred')
            ->setLastname('einstein')
            ;

        $userRepoMocked = $this->createMock(UserRepository::class);
        $userRepoMocked->expects($this->any())
            ->method('findCountLoginUsers')
            ->willReturn($user);

         $this->assertEquals(null, $userRepoMocked->findCountLoginUsers()->getLogincount());
         $user->setLogincount(2);
         $this->assertEquals(2, $userRepoMocked->findCountLoginUsers()->getLogincount());

    }
    protected function tearDown()
    {
        $this->entityManager = null;
    }
}
