<?php

namespace App\Controller\Admin;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickEditController extends AbstractController{

    /**
     * @var TrickRepository
     */
    private $repository;

    public function __construct(TrickRepository $repository)
    {
        $this->repository = $repository;
    }

    public function edit(){

        return $this->render('trick/admin/edit.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }

    public function new(){

        return $this->render('trick/admin/new.html.twig', [
            'controller_name' => 'TricksController',
        ]);
    }
}