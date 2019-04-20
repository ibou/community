<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Tag;

class PostFixtures extends Fixture
{
    // public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    // }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadTags($manager);
 
        $date = new \DateTime('now');
        $post = new Post();
        $post->setTitle('First titles test');
        $post->setSlug('first-titles-test');
        $post->setContent('A content <b>un texte en gras </b>. Ceci est un est exemple de texte');
        $post->setPublishedAt($date);
        $post->setAuthor($this->getReference('testiboudiallo'));
        $post->addTag($this->getReference('tag-social'));
        $manager->persist($post);
        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fname, $lname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFirstname($fname);
            $user->setLastname($lname);
            $user->setUsername($username);
            // $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager)
    {
        foreach ($this->getTagData() as $index => $name) {
            // Tags déja persistées en bdd
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }

        $manager->flush();
    }

    private function getTagData(): array
    {
        return [
            'social',
        ];
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fname, $lname, $username, $password, $email, $roles];
            ['SuperTest', 'AdminTest', 'testiboudiallo', 'testiboudiallo', 'testiboudiallo@symfony.com', ['ROLE_SUPER_ADMIN']],
        ];
    }
}
