<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();
        $accounts = [];
        $categories = [];

        for($i = 0; $i < 50; $i++){
            $account = new Account;
            $account->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setEmail($faker->email())
                    ->setPassword($faker->password())
                    ->setRoles('USER');
            $accounts[] = $account;
            $manager->persist($account);
        }

        for($i = 0; $i < 30; $i++)
        {
            $category = new Category;
            $category->setName($faker->word());
            $categories[] = $category;
            $manager->persist($category);
        }

        for($i = 0; $i < 100; $i++){
            $article = new Article;
            $three = $faker->unique()->randomElements($categories,3);
            $article->setTitle($faker->sentence())
                    ->setContent($faker->text())
                    ->setCreateAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setAuthor($faker->randomElement($accounts))
                    ->addCategory($three[0])
                    ->addCategory($three[1])
                    ->addCategory($three[2]);
            $manager->persist($article);
        }


        $manager->flush();
    }
}
