<?php

namespace App\DataFixtures;

use App\Entity\VideoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class VideoTypeFixtures extends Fixture
{


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $youtubeVideo = new VideoType();

        $youtubeVideo->setSite("Youtube");
        $youtubeVideo->setCode("
            <iframe width=\"1691\" height=\"592\" src=\"https://www.youtube.com/embed/{{code}}\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>
        ");

        $manager->persist($youtubeVideo);


        $dailymotionVideo = new VideoType();

        $dailymotionVideo->setSite("DailyMotion");
        $dailymotionVideo->setCode("
            <iframe frameborder=\"0\" width=\"480\" height=\"270\" src=\"https://www.dailymotion.com/embed/video/{{code}}\" allowfullscreen allow=\"autoplay\"></iframe>
        ");

        $manager->persist($dailymotionVideo);

        $manager->flush();

    }
}