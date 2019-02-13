<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\User\UserRegisteredEvent;
use App\Event\User\UserValidatedEvent;
use App\Form\RegistrationFormType;
use App\Services\FlashMessageCategory;
use App\Security\UserAutoLogon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RegistrationController extends AbstractController
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
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, AuthorizationCheckerInterface $authChecker): Response
    {
        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new UserRegisteredEvent($user, $form->get('plainPassword')->getData());
            $this->dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

            return $this->redirectToRoute('trick.home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
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

    /**
     * @Route("/forgotpassword", name="app_forgotpassword")
     */
    public function forgotPassword(Request $request)
    {

        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        dd($form); //TODO: CSRF invalid

        if ($form->isSubmitted() && $form->isValid()) {
            //get the user from the email or user
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findUserByMailOrUsername($form->getName())
                ;
            dd($user);

            //TODO: Form Posted, Call user event caught by the userRegisteredSubscriber that Registers hash / date and sends mail

            //TODO: Also need a route for the reset password to send the mail
        }


        return $this->render('registration/forgotpassword.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/resetpassword/{token}", name="app_resetpassword", methods={"GET"}, requirements={
     *     "token": "[a-h0-9]*"
     * })
     */
    public function resetPassword(string $token, Request $request)
    {
        //TODO: this will probably use the same validation so make private function ?

        //TODO: get user from session

        //TODO: Generate form
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        //TODO: take care of submitted form

        return $this->render('registration/resetpassword.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
