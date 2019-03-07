<?php

namespace App\EventSubscriber\Trick;

use App\Entity\Trick;
use App\Event\Trick\TrickCreatedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickCreatedSubscriber extends TrickSubscriber implements EventSubscriberInterface
{
    /**
     * Send trick to the database and set a flash message
     * @param TrickCreatedEvent $event
     */
    public function registerTrickToDatabase(TrickCreatedEvent $event)
    {
        /** @var Trick $trick */
        $trick = $event->getEntity();
        $this->sendToDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' created');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TrickCreatedEvent::NAME => 'registerTrickToDatabase'
        ];
    }
}