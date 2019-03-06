<?php

namespace App\Controller\Trick;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchTrickController extends AbstractController
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
