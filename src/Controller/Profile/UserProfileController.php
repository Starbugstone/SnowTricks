<?php

namespace App\Controller\Profile;

use App\Form\UserChangePasswordFormType;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserProfileController
 * @package App\Controller\Profile
 * @IsGranted("ROLE_USER")
 */
class UserProfileController extends AbstractController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/profile", name="admin.profile")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        //Force login, we do not allow the remember me cookie for profile admin.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserProfileFormType::class, $user);
        $form
            ->add('updateProfile', SubmitType::class, [
                'label' => 'Update profile',
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2'
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();
//            dd('Form submitted');
        }

        $formPassword = $this->createForm(UserChangePasswordFormType::class, $user);
        $formPassword
            ->add('updatePassword', SubmitType::class, [
                'label' => 'Update Password',
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2'
                ]
            ]);

        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {


            $password = $formPassword->get('plainPassword')->getData();
//            dd($password);

            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );
            $em->persist($user);
            $em->flush();
            //dd('reset password');
        }
        //dd($user);

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'user' => $user,
        ]);

    }
}