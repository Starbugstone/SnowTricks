<?php

namespace App\EventSubscriber\Exception;

use App\Exception\RedirectException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class RedirectExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @var RouterInterface $router
     */
    private $router;

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'Redirect'
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function Redirect(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof RedirectException) {
            $redirectResponse = new RedirectResponse($this->router->generate($event->getException()->getRedirectResponse()));
            $event->setResponse($redirectResponse);
        }
    }

    /**
     * @required
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }


}