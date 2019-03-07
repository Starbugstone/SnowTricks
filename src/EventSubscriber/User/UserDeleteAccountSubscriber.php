<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserDeleteAccountEvent;
use App\Event\User\UserEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UserDeleteAccountSubscriber extends UserSubscriber implements EventSubscriberInterface
{

    /**
 * @var Session
 */
    private $session;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    public function deleteAccount(UserEvent $event)
    {
        $this->deleteFromDatabase($event);
    }

    public function removeSession()
    {
        $this->tokenStorage->setToken(null);
        $this->session->invalidate();
    }


    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserDeleteAccountEvent::NAME => [
                ['deleteAccount', 50],
                ['removeSession', 40],
            ],
        ];
    }
}