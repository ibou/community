<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\PostLike;
use Faker;

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
        $this->loadTags($manager);
        $this->loadPosts($manager);
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

    private function loadTags(ObjectManager $manager)
    {
        foreach ($this->getTagData() as $index => $name) {
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
            'programmeur',
            'gestion',
            'mathematique',
            'physique',
            'developpeur',
        ];
    }

    private function loadPosts(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        foreach ($this->getPostData() as [$title, $slug,  $content, $publishedAt, $author, $tags]) {
            $post = new Post();
            $post->setTitle($title);
            $post->setSlug($slug);
            $post->setContent($content);
            $post->setPublishedAt($publishedAt);
            $post->setAuthor($author);

            foreach ($tags as $tag) {
                $post->addTag($tag);
            }

            for ($i = 0; $i < 2; ++$i) {
                $like = new PostLike();
                $like->setPost($post);
                if (0 === $i % 2) {
                    $like->setUser($this->getReference('ibou888'));
                } else {
                    $like->setUser($this->getReference('john_user'));
                }
                $manager->persist($like);
            }

            foreach (range(1, 13) as $i) {
                $comment = new Comment();
                if (0 === $i % 2) {
                    $comment->setAuthor($this->getReference('ibou888'));
                } else {
                    $comment->setAuthor($this->getReference('john_user'));
                }
                $comment->setContent($faker->text(random_int(210, 395)));
                $comment->setPublishedAt($faker->dateTimeBetween('-1 year', '-3 days'));
                $comment->setParent(null);
                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fname, $lname, $username, $password, $email, $roles];
            ['Super', 'Admin', 'admin', 'coucou', 'iboudiallo@symfony.com', ['ROLE_SUPER_ADMIN']],
            ['Ibrahima', 'DIALLO', 'ibou888', 'coucou', 'iboudiallo84@gmail.com', ['ROLE_ADMIN']],
            ['Jane ', 'Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom', 'Reynold', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John', 'J. Claude', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
            ['Allen', 'Benerice', 'benericee', 'kitten', 'allenben@symfony.com', ['ROLE_USER']],
        ];
    }

    private function getPostData()
    {
        $faker = Faker\Factory::create('fr_FR');

        $posts = [];
        for ($i = 0; $i < 81; ++$i) {
            $posts[] = [
                $faker->catchPhrase(),
                $faker->slug(),
                $faker->text(random_int(650, 920)),
                $faker->dateTimeBetween('-1 year', '-3 days'),
                // Ensure that the first post is written by Jane Doe to simplify tests
                $this->getReference(['jane_admin', 'tom_admin'][0 === $i ? 0 : random_int(0, 1)]),
                $this->getRandomTags(),
            ];
        }

        return $posts;
    }

    private function getRandomTags(): array
    {
        $tagNames = $this->getTagData();
        shuffle($tagNames);
        $selectedTags = \array_slice($tagNames, 0, random_int(1, 3));

        return array_map(function ($tagName) { return $this->getReference('tag-'.$tagName); }, $selectedTags);
    }
}
