<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
      private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    { 
        $this->loadUsers($manager); 
    }
        

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fname, $lname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFirstname($fname);
            $user->setLastname($lname);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

        private function getUserData(): array
    {
        return [
            // $userData = [$fname, $lname, $username, $password, $email, $roles];
            ['Super','Admin', 'admin', 'coucou', 'iboudiallo@symfony.com', ['ROLE_SUPER_ADMIN']],
            ['Ibrahima','DIALLO', 'ibou888', 'coucou', 'iboudiallo84@gmail.com', ['ROLE_ADMIN']],
            ['Tom','Reynold', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John','Reynold', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
}
