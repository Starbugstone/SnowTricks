<?php

namespace App\Controller\Trick;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SearchTrickController extends AbstractController
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
 * @Route("/search/{categoryId}", name="trick.search")
 */
    public function search($categoryId = "")
    {
        $categories = $this->categoryRepository->findAll();
        $criteria = array();
        if($categoryId !== ""){
            $criteria = array('category' => $categoryId);
        }
        $tricks = $this->trickRepository->findBy($criteria);
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
        ]);
    }
}
