<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class RegistrationController extends AbstractController
{
    private $em;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer)
    {
        $this->em = $em;

        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        AuthorizationCheckerInterface $authChecker
    ): Response {
        //if we are authenticated, no reason to be here
        if ($authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('trick.home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $hash = bin2hex(random_bytes(16));
            $user->setVerifiedHash($hash);

            $this->em->persist($user);
            $this->em->flush();

            //send validation link
            $this->sendHash($user);

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
     *
     */
    public function validate(
        User $user,
        $token,
        EventDispatcherInterface $dispatcher,
        Request $request,
        AuthorizationCheckerInterface $authChecker
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

            //Login user
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));
            $event = new InteractiveLoginEvent($request, $token);
            $dispatcher->dispatch("security.interactive_login", $event);

            return $this->redirectToRoute('app_login');
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
    public function sendVerifiedHash(User $user)
    {
        if(!$user->getVerified()){
            $this->sendHash($user);
        }

        return $this->redirectToRoute('trick.home');
    }

    /**
     * Send the hash link to the user
     * @param User $user
     *
     */
    private function sendHash(User $user)
    {
        $message = (new \Swift_Message('Email validation'))
            ->setFrom('snowtricks@starbugstone.eu')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/hash.html.twig',
                    ['user'=>$user]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
