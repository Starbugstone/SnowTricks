<?php

namespace App\EventSubscriber\Trick;

use App\Event\Trick\TrickDeletedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TrickDeletedSubscriber extends AbstractTrickSubscriber implements EventSubscriberInterface
{
    /**
     * Send trick to the database and set a flash message
     * @param TrickDeletedEvent $event
     */
    public function deleteTrickFromDatabase(TrickDeletedEvent $event)
    {
        $trick = $event->getEntity();
        $this->deleteFromDatabase($event);
        $this->addFlashMessage(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' Deleted');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TrickDeletedEvent::NAME => 'deleteTrickFromDatabase'
        ];
    }
}