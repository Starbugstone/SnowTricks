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
     * @Route("/trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Trick $trick, string $slug)
    {

        //Checking if slug is equal to the ID. This is for SEO and external links
        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ], 301);
        }

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
