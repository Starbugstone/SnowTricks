<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserValidatedEvent;
use App\Security\UserAutoLogon;
use App\FlashMessage\FlashMessageCategory;
use App\Security\UserValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ValidateController extends AbstractController
{

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/user/validate/{token}", name="app_validate", methods={"GET"}, requirements={
     *     "token": "[a-h0-9]*"
     * })
     */
    public function validate(
        string $token,
        AuthorizationCheckerInterface $authChecker,
        UserAutoLogon $autoLogon,
        UserValidator $userValidator
    ) {
        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        if ($userValidator->isUserTokenValid($token)) {

            $event = new UserValidatedEvent($user);
            $this->dispatcher->dispatch(UserValidatedEvent::NAME, $event);

            //autologon
            $autoLogon->autoLogon($user);

            return $this->redirectToRoute('home');
        }

        //Error, redirect to the forgot password
        $this->addFlash(FlashMessageCategory::ERROR,
            'Your verification link is no longer valid, please use this form to resend a link');
        return $this->redirectToRoute('app_forgotpassword');
    }

}
