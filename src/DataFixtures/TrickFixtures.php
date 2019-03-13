<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $categoryList = array();
        for($i=0; $i<5; $i++){
            $category = new Category();
            $categoryList[] = $category->setName($faker->sentence(5));
            $manager->persist($category);
        }

        for ($i=0; $i<25; $i++){
            $trick = new Trick();
            $trick
                ->setName($faker->words(rand(1,5), true))
                ->setText($faker->paragraph(rand(3,20)))
                ->setCreatedAt($faker->dateTimeThisDecade())
                ->setCategory($categoryList[rand(0,4)])
            ;

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
