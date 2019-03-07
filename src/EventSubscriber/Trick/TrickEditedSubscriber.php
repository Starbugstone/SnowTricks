<?php

namespace App\EventSubscriber\Trick;

use App\Entity\Trick;
use App\Event\Trick\TrickEditedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickEditedSubscriber extends TrickSubscriber implements EventSubscriberInterface
{
    /**
     * Send trick to the database and set a flash message
     * @param TrickEditedEvent $event
     */
    public function updateTrickInDatabase(TrickEditedEvent $event)
    {
        /** @var Trick $trick */
        $trick = $event->getEntity();
        $this->sendToDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' updated');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TrickEditedEvent::NAME => 'updateTrickInDatabase'
        ];
    }
}