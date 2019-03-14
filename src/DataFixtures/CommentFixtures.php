<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


class CommentFixtures extends Fixture implements DependentFixtureInterface
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
            UserFixtures::class,
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

        $users = $manager->getRepository(User::class)->findAll();
        $tricks = $manager->getRepository(Trick::class)->findAll();

        /** @var Trick $trick */
        foreach ($tricks as $trick) {
            $commentNumber = rand(0, 40);
            if ($commentNumber > 0) {
                for ($i = 0; $i <= $commentNumber; $i++) {
                    ///** @var User $user */
                    //$user = $users[rand(0, count($users))];
                    $comment = new Comment();
                    $comment->setTrick($trick);
                    $comment->setUser($faker->randomElement($users));
                    $comment->setComment($faker->realText(rand(10,75)));

                    $manager->persist($comment);

                }
            }
        }

        $manager->flush();
    }
}