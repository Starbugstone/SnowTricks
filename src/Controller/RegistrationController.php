<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
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

            // do anything else you need here, like send an email
            //TODO Send email with URL/validate/id/token

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
    public function validate(User $user, $token)
    {
        if($user->getVerified())
        {
            //Account already active
            return $this->redirectToRoute('app_login');
        }

        //checking if the timestamp is valid and saving the result
        $now = new \DateTime();
        $timestampValid = $now->getTimestamp()-$user->getVerifiedDateTime()->getTimestamp()<= User::HASH_VALIDATION_TIME_LIMIT*60*60*24;

        //checking the hash
        if($user->getVerifiedHash() === $token && $timestampValid){
            $user->setVerified(true);
            $this->em->flush();
            return $this->redirectToRoute('app_login');
        }
        else{
            dd("Bad hash or time");
        }
    }
}
