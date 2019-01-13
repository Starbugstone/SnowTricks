<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick.home")
     */
    public function index()
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }


    /**
     * @Route("/trick/{id}" name="trick.show")
     */
    public function show($id) //todo: change ID to slug
    {
        return $this->render('trick/show.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }
}
