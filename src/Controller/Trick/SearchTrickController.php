<?php

namespace App\Controller\Trick;

use App\Exception\RedirectException;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/search", name="trick.searchTrick", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * Get the results from the search query
     */
    public function searchTrick(Request $request)
    {
        $submittedToken = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('search-trick', $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $categories = $this->categoryRepository->findAll();
        $searchTerm = $request->request->get('search_trick');
        $tricks = $this->trickRepository->findBySearchQuery($searchTerm);
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
            'categoryId' =>"",
            'searchTerm' =>$searchTerm,
        ]);

    }

    /**
     * @Route("/search/{categoryId}", name="trick.search", methods={"GET"})
     * @param string $categoryId
     * @return \Symfony\Component\HttpFoundation\Response
     * show tricks in category
     */
    public function search($categoryId = "")
    {
        $categories = $this->categoryRepository->findAll();
        $criteria = array();
        if ($categoryId !== "") {
            $criteria = array('category' => $categoryId);
        }
        $tricks = $this->trickRepository->findBy($criteria);
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
            'categoryId' =>$categoryId,
        ]);
    }
}
