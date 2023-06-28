<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// ENTITIES
use App\Entity\Users;
use App\Entity\Posts;
use App\Entity\Comments;
// FAKER
use Faker;

class AppFixtures extends Fixture
{
    private const MAX_USER = 15;
    private const MAX_POST_AUTHOR = 20;
    private const MAX_POST_ADMIN = 10;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        // $populator = new \Faker\ORM\Propel\Populator($faker);
        // Création d'un compte Admin
        $admin = new Users();
        $admin
            ->setEmail('a@a.fr')
            ->setUsername('Admin')
            ->setPassword($this->hasher->hashPassword($admin, 'Azerty$0'))
            ->setRoles(["ROLE_ADMIN"])
            ->setIsverified(TRUE);

        $manager->persist($admin);

        // Création d'un compte Author
        $author = new Users();
        $author
            ->setEmail('b@b.fr')
            ->setUsername('author')
            ->setPassword($this->hasher->hashPassword($author, 'Azerty$0'))
            ->setRoles(["ROLE_AUTHOR"])
            ->setIsverified(TRUE);

        $manager->persist($author);

        // Création d'un compte en role User
        $user = new Users();
        $user
            ->setEmail('c@c.fr')
            ->setUsername('user')
            ->setPassword($this->hasher->hashPassword($user, 'Azerty$0'))
            ->setIsverified(TRUE);

        $manager->persist($user);

        // Création de MAX_USER comptes aléatoires avec le ROLE_USER
        for ($i = 0; $i < self::MAX_USER; $i++) {
            $user = new Users();
            $user
                ->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setPassword($this->hasher->hashPassword($user, $faker->password(8, 20)))
                ->setIsverified(TRUE);

            $manager->persist($user);
        }

        // Création de MAX_POST_ADMIN articles avec le compte Admin
        for ($i = 0; $i < self::MAX_POST_ADMIN; $i++) {
            $post = new Posts();
            $post
                ->setTitle($faker->realText($maxNbChars = 100))
                ->setContent($faker->realText($maxNbChars = 2000))
                ->setAuthor($admin);

            $manager->persist($post);
        }

        // Création de MAX_POST_ADMIN articles avec le compte Admin
        for ($i = 0; $i < self::MAX_POST_AUTHOR; $i++) {
            $post = new Posts();
            $post
                ->setTitle($faker->realText($maxNbChars = 100))
                ->setContent($faker->realText($maxNbChars = 2000))
                ->setAuthor($author);

            $manager->persist($post);
        }
        $manager->flush();
    }
}
