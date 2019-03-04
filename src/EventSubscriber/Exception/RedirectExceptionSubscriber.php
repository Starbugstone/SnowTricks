<?php

namespace App\EventSubscriber\Exception;

use App\Exception\RedirectException;
use App\FlashMessage\AddFlashTrait;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;


class RedirectExceptionSubscriber implements EventSubscriberInterface
{
    use AddFlashTrait;

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
            $redirectResponse = new RedirectResponse($event->getException()->getRedirectResponse());

            if($event->getException()->getMessage() !== ""){
                $this->addFlash(FlashMessageCategory::ERROR, $event->getException()->getMessage());
            }

            $event->setResponse($redirectResponse);
        }
    }

}