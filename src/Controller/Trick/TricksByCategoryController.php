<?php

namespace App\Controller\Trick;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TricksByCategoryController extends AbstractController
{

    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(TrickRepository $trickRepository, CategoryRepository $categoryRepository)
    {
        $this->trickRepository = $trickRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/trick/category/{categoryId}-{slug}", name="trick.search", methods={"GET"})
     * @param string $categoryId
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * show tricks in category
     */
    public function search($categoryId = "", $slug = "")
    {
        $categories = $this->categoryRepository->findAll();
        $criteria = array();
        if ($categoryId !== "") {
            $category = $this->categoryRepository->find($categoryId);
            if($category->getSlug() !== $slug){
                return $this->redirectToRoute('trick.search', [
                    'id' => $category->getId(),
                    'slug' => $category->getSlug()
                ], 301);
            }
            $criteria = array('category' => $category->getId());
        }
        $tricks = $this->trickRepository->findBy($criteria);
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
            'categoryId' =>$categoryId,
        ]);
    }
}