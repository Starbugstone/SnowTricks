<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserForgotpasswordEvent;
use App\FlashMessage\FlashMessageCategory;
use App\Form\ForgotpasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
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
     * @Route("/user/forgotpassword", name="app_forgotpassword")
     */
    public function forgotPassword(Request $request)
    {

        $form = $this->createForm(ForgotpasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //get the user object from the email or user
            //this smells a bit as I don't like calls in a controller. But I don't want to redo a service just for a simple doctrine call
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findUserByMailOrUsername($form->get('userName')->getData());

            if ($user) {//Only send mail if an account was found
                $event = new UserForgotpasswordEvent($user);
                $this->dispatcher->dispatch(UserForgotpasswordEvent::NAME, $event);
            }

            //Do not say if account was found or not to avoid robots testing for emails. This can still be tested by a hacker by calculating the reply time but not as easy.
            $this->addFlash(FlashMessageCategory::INFO,
                'If you have an account, then an email has been sent to your registered email');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/forgotpassword.html.twig', [
            'forgotpasswordForm' => $form->createView(),
        ]);
    }
}