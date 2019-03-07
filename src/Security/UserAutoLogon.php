<?php

namespace App\Security;

use App\Entity\User;
use Exception;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
     * @return Event
     * Auto Logs on the passed user
     * @throws Exception
     */
    public function autoLogon(User $user): Event
    {
        $request = $this->requestStack->getCurrentRequest();
        if($request === null){
            throw new Exception('request is null');
        }
        //Login user
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        return $this->dispatcher->dispatch("security.interactive_login", $event);
    }
}