<?php

namespace App\EventSubscriber\Trick;

use App\Event\Trick\TrickDeletedEvent;
use App\Services\FlashMessageCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TrickDeletedSubscriber extends TrickSubscriber implements EventSubscriberInterface
{


    /**
     * Send trick to the database and set a flash message
     * @param TrickDeletedEvent $event
     */
    public function deleteTrickFromDatabase(TrickDeletedEvent $event)
    {
        $trick = $event->getTrick();
        $this->em->remove($trick);
        $this->em->flush();
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Trick '.$trick->getName().' Deleted');
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