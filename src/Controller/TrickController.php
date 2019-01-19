<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    /**
     * @var TrickRepository
     */
    private $repository;

    public function __construct(TrickRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="trick.home")
     */
    public function index()
    {
        $tricks = $this->repository->findAll();
        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick.show")
     */
    public function show(Trick $trick) //todo: change ID to slug
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/search", name="trick.search")
     */
    public function search()
    {
        $tricks = $this->repository->findAll();
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
