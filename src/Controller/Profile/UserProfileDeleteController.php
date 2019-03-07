<?php

namespace App\Controller\Profile;

use App\Event\User\UserDeleteAccountEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class UserProfileDeleteController
 * @package App\Controller\Profile
 * @IsGranted("ROLE_USER")
 */
class UserProfileDeleteController extends AbstractController
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
     * @Route("/profile/delete", name="admin.delete_profile")
     */
    public function deleteProfile(Request $request, TokenStorageInterface $tokenStorage)
    {
        //TODO:; Csrf Protection
        $user = $this->getUser();

        $event = new UserDeleteAccountEvent($user);
        $this->dispatcher->dispatch(UserDeleteAccountEvent::NAME, $event);

        $tokenStorage->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('home');

    }
}