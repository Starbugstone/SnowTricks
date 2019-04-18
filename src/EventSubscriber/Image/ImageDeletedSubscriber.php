<?php

namespace App\EventSubscriber\Image;


use App\Event\Image\ImageDeleteEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageDeletedSubscriber extends AbstractImageSubscriber implements EventSubscriberInterface
{

    public function deleteImageFromDatabase(ImageDeleteEvent $event)
    {
        $image = $event->getEntity();

        $trick = $event->getTrick();

        $trick->removeImage($image);
        $this->em->persist($trick);
        $this->em->flush();


        $this->addFlash(FlashMessageCategory::SUCCESS, 'image ' . $image->getTitle() . ' deleted');

    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ImageDeleteEvent::NAME => 'deleteImageFromDatabase',
        ];
    }


}