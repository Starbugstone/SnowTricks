<?php

namespace App\Controller\Profile;

use App\Event\User\UserChangepasswordEvent;
use App\Event\User\UserUpdateAccountEvent;
use App\Form\UserChangePasswordFormType;
use App\Form\UserProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserProfileController
 * @package App\Controller\Profile
 * @IsGranted("ROLE_USER")
 */
class UserProfileController extends AbstractController
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
     * @Route("/profile", name="admin.profile")
     */
    public function index(Request $request)
    {
        //Force login, we do not allow the remember me cookie for profile admin.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserProfileFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new UserUpdateAccountEvent($user);
            $this->dispatcher->dispatch(UserUpdateAccountEvent::NAME, $event);
        }

        $formPassword = $this->createForm(UserChangePasswordFormType::class, $user);

        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {

            $password = $formPassword->get('plainPassword')->getData();

            $event = new UserChangepasswordEvent($user, $password);
            $this->dispatcher->dispatch(UserChangepasswordEvent::NAME, $event);
        }


        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'user' => $user,
        ]);

    }
}