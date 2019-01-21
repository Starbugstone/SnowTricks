<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i=0; $i<25; $i++){
            $trick = new Trick();
            $trick
                ->setName($faker->words(3, true))
                ->setText($faker->paragraph())
                ->setCreatedAt($faker->dateTimeThisDecade())
            ;

            $manager->persist($trick);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
