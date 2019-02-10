<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\User\UserRegisteredEvent;
use App\Form\RegistrationFormType;
use App\Services\Registration\RegistrationAutoLogon;
use App\Services\Registration\RegistrationMailer;
use App\Services\UserSetHash;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AuthorizationCheckerInterface $authChecker,
        RegistrationMailer $registrationMailer,
        UserSetHash $registrationSetHash
    ): Response {
        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //---Move To event

            $event = new UserRegisteredEvent($user, $form->get('plainPassword')->getData());
            $this->dispatcher->dispatch(UserRegisteredEvent::NAME, $event);

            /*// encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            */
            //$registrationSetHash->setHash($user);

            //send validation link
            //$registrationMailer->sendHash($user);

            //$this->addFlash('success', 'Account created, we have sent an email to ' . $user->getEmail() . ' with a validation link');

            //---End Move to event

            return $this->redirectToRoute('trick.home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/validate/{id}/{token}", name="app_validate", methods={"GET"}, requirements={
     *     "id": "\d+",
     *     "token": "[a-h0-9]*"
     * })
     * @param User $user
     * @param $token
     * @param AuthorizationCheckerInterface $authChecker
     * @param RegistrationAutoLogon $autoLogon
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function validate(
        User $user,
        $token,
        AuthorizationCheckerInterface $authChecker,
        RegistrationAutoLogon $autoLogon,
        Request $request
    ) {

        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        if ($user->getVerified()) {
            //Account already active
            return $this->redirectToRoute('app_login');
        }

        //checking the hash and valid date
        if ($user->isHashValid($token) && $user->isVerifiedDateTimeValid()) {
            $user->setVerified(true);
            $this->em->flush();

            $this->addFlash('success', 'Account is verified');

            //autologon
            $autoLogon->autoLogon($user, $request);

            return $this->redirectToRoute('trick.home');
        }
        return $this->render('registration/error.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/resendhash/{id}", name="app_resendhash", requirements={
     *     "id": "\d+"
     * })
     */
    public function sendVerifiedHash(User $user, RegistrationMailer $registrationMailer, UserSetHash $registrationSetHash)
    {
        if (!$user->getVerified()) {
            $registrationSetHash->setHash($user);
            $registrationMailer->sendHash($user);
            $this->addFlash('success', 'Verification link sent to ' . $user->getEmail());
        }
        return $this->redirectToRoute('trick.home');
    }
}
