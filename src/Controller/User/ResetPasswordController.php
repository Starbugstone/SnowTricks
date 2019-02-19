<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserResetpasswordEvent;
use App\Event\User\UserValidatedEvent;
use App\FlashMessage\FlashMessageCategory;
use App\Form\ResetpasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
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
     * @Route("/resetpassword/{token}", name="app_resetpassword", methods={"GET", "POST"}, requirements={
     *     "token": "[a-h0-9]*"
     * })
     */
    public function resetPassword(string $token, Request $request, AuthorizationCheckerInterface $authChecker)
    {
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

        if (!$user->isVerifiedDateTimeValid()) {
            //the link is no longer valid
            $this->addFlash(FlashMessageCategory::ERROR, 'Token is too old, please use this form to resend a link');
            return $this->redirectToRoute('app_forgotpassword');
        }

        //If we got here then we followed a reset link from email. We can verify mail
        if (!$user->getVerified()) {
            $event = new UserValidatedEvent($user);
            $this->dispatcher->dispatch(UserValidatedEvent::NAME, $event);
        }

        $form = $this->createForm(ResetpasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new UserResetpasswordEvent($user, $form->get('plainPassword')->getData());
            $this->dispatcher->dispatch(UserResetpasswordEvent::NAME, $event);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('validation/resetpassword.html.twig', [
            'resetpasswordForm' => $form->createView(),
            'userEmail' => $user->getEmail(),
        ]);
    }
}