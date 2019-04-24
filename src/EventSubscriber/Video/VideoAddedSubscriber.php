<?php

namespace App\EventSubscriber\Video;

use App\Event\Video\VideoAddEvent;
use App\Event\Video\AbstractVideoEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoAddedSubscriber extends AbstractVideoSubscriber implements EventSubscriberInterface
{
    /**
     * Send Image to the database and set a flash message
     * @param AbstractVideoEvent $event
     */
    public function registerVideoToDatabase(AbstractVideoEvent $event)
    {
        $video = $event->getEntity();

        $trick = $event->getTrick();

        $trick->addVideo($video);
        $this->em->persist($trick);
        $this->em->flush();

        $this->addFlashMessage(FlashMessageCategory::SUCCESS, 'Video '. $video->getTitle() .' Added');
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