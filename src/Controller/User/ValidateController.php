<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserValidatedEvent;
use App\Security\UserAutoLogon;
use App\FlashMessage\FlashMessageCategory;
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
     * @Route("/validate/{token}", name="app_validate", methods={"GET"}, requirements={
     *     "token": "[a-h0-9]*"
     * })
     */
    public function validate(
        string $token,
        AuthorizationCheckerInterface $authChecker,
        UserAutoLogon $autoLogon
    ) {
        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByHash($token);

        if (!$user) {
            //no user found
            $this->addFlash(FlashMessageCategory::ERROR, 'Invalid Token, please use this form to resend a link');
            return $this->redirectToRoute('app_forgotpassword');
        }

        if ($user->getVerified()) {
            //Account already active
            $this->addFlash(FlashMessageCategory::INFO, 'Mail already verified');
            return $this->redirectToRoute('app_login');
        }

        //checking the date
        if ($user->isVerifiedDateTimeValid()) {

            $event = new UserValidatedEvent($user);
            $this->dispatcher->dispatch(UserValidatedEvent::NAME, $event);

            //autologon
            $autoLogon->autoLogon($user);

            return $this->redirectToRoute('trick.home');
        }

        //Error, redirect to the forgot password
        $this->addFlash(FlashMessageCategory::ERROR,
            'Your verification link is no longer valid, please use this form to resend a link');
        return $this->redirectToRoute('app_forgotpassword');
    }

}
