<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserAutoLogon
{

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(EventDispatcherInterface $dispatcher, TokenStorageInterface $tokenStorage, SessionInterface $session, RequestStack $requestStack)
    {
        $this->dispatcher = $dispatcher;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->requestStack = $requestStack;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Event
     * Auto Logges on the passed user
     *
     */
    public function autoLogon(User $user): Event
    {
        $request = $this->requestStack->getCurrentRequest();
        //Login user
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        return $this->dispatcher->dispatch("security.interactive_login", $event);
    }
}