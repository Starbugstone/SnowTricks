<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
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
            $manager->flush();
            //var_dump($trick->getId()); //use this for the trick images folder

            //create associated images
//            for ($i=0; $i < rand(0,6); $i++){
//                $image = new Image();
//                $image->setTitle($faker->word(rand(1,7), true));
//                //error if the folder doesn't exist. make folder with trick ID
//                $image->setImage($faker->image('public/uploads/images/'.$trick->getId().'/', 400, 300, null, false));
//            }
        }

        $manager->flush();
    }
}
