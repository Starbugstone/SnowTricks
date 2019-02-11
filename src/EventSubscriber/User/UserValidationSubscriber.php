<?php

namespace App\EventSubscriber\User;

use App\Entity\User;
use App\Event\User\UserValidationEvent;
use App\Services\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserValidationSubscriber extends UserSubscriber implements EventSubscriberInterface
{

    private $user;

    public function retrieveUser(UserValidationEvent $event)
    {
        $token = $event->getToken();
        $this->user = $this->em
            ->getRepository(User::class)
            ->findUserByHash($token)
            ;
    }

    public function validateUser(UserValidationEvent $event)
    {
        if(!$this->user){
            $this->addFlash(FlashMessageCategory::ERROR, 'Invalid Token, please use the forgot password form');
            //no user in DB
            //TODO: redirect to forgotten password
        }

        if ($user->getVerified()) {
            //Account already active, login
            //$autoLogon->autoLogon($user/*, $request*/);
            //return $this->redirectToRoute('trick.home');
        }


    }



    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserValidationEvent::NAME => [
                ['retrieveUser', 50],
                ['validateUser', 40],
            ]
        ];
    }
}