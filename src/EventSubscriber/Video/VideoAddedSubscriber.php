<?php

namespace App\EventSubscriber\Video;

use App\Entity\Video;
use App\Entity\Trick;
use App\Event\Video\VideoAddEvent;
use App\Event\Video\VideoEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoAddedSubscriber extends VideoSubscriber implements EventSubscriberInterface
{
    /**
     * Send Image to the database and set a flash message
     * @param VideoEvent $event
     */
    public function registerVideoToDatabase(VideoEvent $event)
    {
        $video = $event->getEntity();

        $trick = $event->getTrick();

        $trick->addVideo($video);
        $this->em->persist($trick);
        $this->em->flush();

        $this->addFlash(FlashMessageCategory::SUCCESS, 'Video '. $video->getTitle() .' Added');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            VideoAddEvent::NAME => 'registerVideoToDatabase',
        ];
    }
}