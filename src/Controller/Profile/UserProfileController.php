<?php

namespace App\Controller\Profile;

use App\Form\UserProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserProfileController
 * @package App\Controller\Profile
 * @IsGranted("ROLE_USER")
 */
class UserProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="admin.profile")
     */
    public function index()
    {
        //Force login, we do not allow the remember me cookie for profile admin.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $form = $this->createForm(UserProfileFormType::class, $user);

        //dd($user);

        return $this->render('user/profile.html.twig',[
            'form' => $form->createView(),
        ]);

    }
}