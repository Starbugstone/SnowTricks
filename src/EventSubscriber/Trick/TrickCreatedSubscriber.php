<?php

namespace App\EventSubscriber\Trick;

use App\Event\Trick\TrickCreatedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\Event\Trick\TrickEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickCreatedSubscriber extends TrickSubscriber implements EventSubscriberInterface
{

    /**
     * Send trick to the database and set a flash message
     * @param TrickEvent $event
     */
    public function registerTrickToDatabase(TrickEvent $event)
    {
        $trick = $event->getEntity();
        $this->sendToDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' saved');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        //TODO: set primary image
        return [
            TrickCreatedEvent::NAME => [
                ['registerTrickToDatabase', 30],
            ],
            TrickEditedEvent::NAME => [
                ['registerTrickToDatabase', 30],
            ],
        ];
    }
}