<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserValidatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserValidatedSubscriber extends UserSubscriber implements EventSubscriberInterface
{


    public function validateUser(UserValidatedEvent $event)
    {
        $user = $event->getEntity();
        $user->setVerified(true);
        $this->persist($event);
    }

    public function sendFlash()
    {
        $this->addFlash('success', 'Account is verified');
    }


    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserValidatedEvent::NAME => [
                ['validateUser', 40],
                ['flush', 20],
                ['sendFlash', 0],
            ]
        ];
    }
}