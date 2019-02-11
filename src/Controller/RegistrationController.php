<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\User\UserRegisteredEvent;
use App\Form\RegistrationFormType;
use App\Services\FlashMessageCategory;
use App\Services\UserAutoLogon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RegistrationController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
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
        $token,
        AuthorizationCheckerInterface $authChecker,
        UserAutoLogon $autoLogon
    ) {

        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByHash($token)
            ;

        //no user found
        if (!$user){

            $this->addFlash(FlashMessageCategory::ERROR, 'Invalid Token, please use the forgot password form');
            return $this->redirectToRoute('app_forgotpassword');
        }

        if ($user->getVerified()) {
            //Account already active, login
            $autoLogon->autoLogon($user/*, $request*/);
            return $this->redirectToRoute('trick.home');
        }

        //checking the hash and valid date
        //if ($user->isHashValid($token) && $user->isVerifiedDateTimeValid()) { //do not need the isHashValid since we got the user via hash
        if ($user->isVerifiedDateTimeValid()) {
            $user->setVerified(true);
            $this->em->flush();

            $this->addFlash('success', 'Account is verified');

            //autologon
            $autoLogon->autoLogon($user/*, $request*/);

            return $this->redirectToRoute('trick.home');
        }

        //Error, redirect to the forgot password
        return $this->redirectToRoute('app_forgotpassword');
    }

    /**
     * @Route("/resendhash/{id}", name="app_resendhash", requirements={
     *     "id": "\d+"
     * })
     */
    //TODO: this will be taken care of with the forgot password
    /*public function sendVerifiedHash(User $user, RegistrationMailer $registrationMailer, UserSetHash $registrationSetHash)
    {
        if (!$user->getVerified()) {
            $registrationSetHash->setHash($user);
            $registrationMailer->sendHash($user);
            $this->addFlash('success', 'Verification link sent to ' . $user->getEmail());
        }
        return $this->redirectToRoute('trick.home');
    }*/

    /**
     * @Route("/forgotpassword", name="app_forgotpassword")
     */
    public function forgotPassword(){

        //TODO: Form Posted, send mail

        //TODO: show forgot password form, for now just reusing the Error template
        return $this->render('registration/error.html.twig', [

        ]);
    }
}
