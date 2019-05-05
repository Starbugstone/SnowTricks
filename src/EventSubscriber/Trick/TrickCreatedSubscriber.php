<?php

namespace App\EventSubscriber\Trick;

use App\Entity\Image;
use App\Event\Trick\AbstractTrickEvent;
use App\Event\Trick\TrickCreatedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickCreatedSubscriber extends AbstractTrickSubscriber implements EventSubscriberInterface
{

    /**
     * Setting the first image as primary as specified in the wireframes
     * @param AbstractTrickEvent $event
     */
    public function setFirstImageAsPrimary(AbstractTrickEvent $event){
        $trick = $event->getEntity();
        if($trick->getImages()->count() >0){
            /** @var Image $primImage */
            $primImage = $trick->getImages()->first();
            $primImage->setPrimaryImage(true);
            $this->em->persist($primImage);
            $this->em->flush();
        }
    }

    /**
     * Send trick to the database and set a flash message
     * @param AbstractTrickEvent $event
     */
    public function registerTrickToDatabase(AbstractTrickEvent $event)
    {
        $trick = $event->getEntity();
        $this->sendToDatabase($event);
        $this->addFlashMessage(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' saved');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TrickCreatedEvent::NAME => [
                ['setFirstImageAsPrimary', 50],
                ['registerTrickToDatabase', 30],
            ],
            TrickEditedEvent::NAME => [
                ['registerTrickToDatabase', 30],
            ],
        ];
    }
}