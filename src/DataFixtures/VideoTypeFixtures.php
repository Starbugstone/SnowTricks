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
            <iframe width=\"100%\" height=\"100%\" src=\"https://www.youtube.com/embed/{{code}}\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>
        ");
        $youtubeVideo->setImageCode("https://img.youtube.com/vi/{{code}}/1.jpg");

        $manager->persist($youtubeVideo);


        $dailymotionVideo = new VideoType();

        $dailymotionVideo->setSite("DailyMotion");
        $dailymotionVideo->setCode("
            <iframe frameborder=\"0\" width=\"100%\" height=\"100%\" src=\"https://www.dailymotion.com/embed/video/{{code}}\" allowfullscreen allow=\"autoplay\"></iframe>
        ");
        $dailymotionVideo->setImageCode("https://www.dailymotion.com/thumbnail/video/{{code}}");


        $manager->persist($dailymotionVideo);

        $twitchVideo = new VideoType();

        $twitchVideo->setSite("Twitch");
        $twitchVideo->setCode("
            <iframe src=\"https://player.twitch.tv/?autoplay=false&video={{code}}\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\" height=\"100%\" width=\"100%\"></iframe>
        ");
        $twitchVideo->setImageCode("https://www.twitch.tv/p/assets/uploads/combologo_474x356.png");

        $manager->persist($twitchVideo);

        $manager->flush();

    }
}