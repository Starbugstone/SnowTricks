<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            TrickFixtures::class,
        );
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $tricks = $manager->getRepository(Trick::class)->findAll();

        foreach ($tricks as $trick){
            $maxTags = rand(0,5);
            if($maxTags >0){
                for($i=0; $i<=$maxTags; $i++){
                    $tag=new Tag();
                    $tag->setName($faker->unique()->words(rand(1,3), true));
                    $trick->addTag($tag);
                }
            }
            $manager->persist($trick);
        }

        $manager->flush();

    }
}