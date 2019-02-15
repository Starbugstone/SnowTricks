<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserRegisteredEvent;
use App\Event\User\UserForgotpasswordEvent;
use App\Form\ForgotpasswordFormType;
use App\Form\RegistrationFormType;
use App\Services\FlashMessageCategory;
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
     * @Route("/forgotpassword", name="app_forgotpassword")
     */
    public function forgotPassword(Request $request)
    {

        $form = $this->createForm(ForgotpasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //get the user object from the email or user
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findUserByMailOrUsername($form->get('userName')->getData());

            if ($user) {//Only send mail if an account was found
                $event = new UserForgotpasswordEvent($user);
                $this->dispatcher->dispatch(UserForgotpasswordEvent::NAME, $event);
            }

            //Do not say if account was found or not to avoid robots testing for emails. This can still be tested by a hacker by calculating the reply time but not as easy.
            $this->addFlash(FlashMessageCategory::INFO, 'If you have an account, then an email has been sent to your registered email');
            return $this->redirectToRoute('trick.home');
        }

        return $this->render('registration/forgotpassword.html.twig', [
            'forgotpasswordForm' => $form->createView(),
        ]);
    }



}
