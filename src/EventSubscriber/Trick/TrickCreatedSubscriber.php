<?php

namespace App\EventSubscriber\Trick;

use App\Entity\Image;
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
//        dd($trick);
        if(count($trick->getImages())>0){
            /** @var Image $image */
            foreach($trick->getImages() as $image){
                $image->setTrick($trick);
                $this->em->persist($image);
            }
        }
//        $this->sendToDatabase($event);
        $this->em->persist($trick);
        $this->em->flush();
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Trick ' . $trick->getName() . ' saved');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TrickCreatedEvent::NAME => 'registerTrickToDatabase',
            TrickEditedEvent::NAME => 'registerTrickToDatabase',
        ];
    }
}