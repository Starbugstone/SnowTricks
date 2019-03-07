<?php

namespace App\Controller\Profile;

use App\Entity\User;
use App\Event\User\UserDeleteAccountEvent;
use App\Exception\RedirectException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


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
    public function deleteProfile(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-profile'.$user->getId(), $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }



        $event = new UserDeleteAccountEvent($user);
        $this->dispatcher->dispatch(UserDeleteAccountEvent::NAME, $event);

        return $this->redirectToRoute('home');

    }
}