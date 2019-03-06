<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController{

    /**
     * @Route("/profile", name="admin.profile")
     */
    public function index(){
        $user = $this->getUser();
        dd($user);

    }
}