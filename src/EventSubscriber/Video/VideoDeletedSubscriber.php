<?php

namespace App\EventSubscriber\Video;

use App\Event\Video\VideoDeleteEvent;
use App\Event\Video\AbstractVideoEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VideoDeletedSubscriber extends AbstractVideoSubscriber implements EventSubscriberInterface
{
    /**
     * Send Image to the database and set a flash message
     * @param AbstractVideoEvent $event
     */
    public function deleteVideoFromDatabase(AbstractVideoEvent $event)
    {
        $video = $event->getEntity();

        $trick = $event->getTrick();

        $trick->removeVideo($video);
        $this->em->persist($trick);
        $this->em->flush();

        $this->addFlash(FlashMessageCategory::SUCCESS, 'Video '. $video->getTitle() .' deleted');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            VideoDeleteEvent::NAME => 'deleteVideoFromDatabase',
        ];
    }
}