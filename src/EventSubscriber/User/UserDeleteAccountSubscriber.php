<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserDeleteAccountEvent;
use App\Event\User\UserEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UserDeleteAccountSubscriber extends UserSubscriber implements EventSubscriberInterface
{

//    /**
//     * @var Session
//     */
//    private $session;
//    /**
//     * @var TokenStorageInterface
//     */
//    private $tokenStorage;
//
//    public function __construct(Session $session, TokenStorageInterface $tokenStorage)
//    {
//        $this->session = $session;
//        $this->tokenStorage = $tokenStorage;
//    }

    public function deleteAccount(UserEvent $event)
    {
        /** @var \App\Entity\User $user */
        $user = $event->getEntity();
        $this->deleteFromDatabase($event);
        $this->addFlash(FlashMessageCategory::INFO, 'account '.$user->getUsername().' deleted');

    }

//    public function removeSession(UserEvent $event){
//        $this->get('security.context')->setToken(null);
//        $this->get('request')->getSession()->invalidate();
//    }


    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserDeleteAccountEvent::NAME => [
                ['deleteAccount', 50],
            ],
        ];
    }
}