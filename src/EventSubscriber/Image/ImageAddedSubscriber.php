<?php

namespace App\EventSubscriber\Image;


use App\Entity\Image;
use App\Entity\Trick;
use App\Event\Image\ImageAddEvent;
use App\Event\Image\AbstractImageEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageAddedSubscriber extends AbstractImageSubscriber implements EventSubscriberInterface
{
    /**
     * Send Image to the database and set a flash message
     * @param AbstractImageEvent $event
     */
    public function registerImageToDatabase(AbstractImageEvent $event)
    {
        /**@var Image $image*/
        $image = $event->getEntity();
//        $this->sendToDatabase($event);
        /** @var Trick $trick   */
        $trick = $event->getTrick();
        $trick->addImage($image);
        $this->em->persist($trick);
        $this->em->flush();

        $this->addFlash(FlashMessageCategory::SUCCESS, 'Image Added');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ImageAddEvent::NAME => 'registerImageToDatabase',
        ];
    }
}