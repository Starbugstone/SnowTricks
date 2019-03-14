<?php

namespace App\Controller\Trick;

use App\Exception\RedirectException;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use App\Search\TrickSearch;
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
    /**
     * @var TrickSearch
     */
    private $search;

    public function __construct(TrickRepository $trickRepository, CategoryRepository $categoryRepository, TrickSearch $search)
    {
        $this->trickRepository = $trickRepository;
        $this->categoryRepository = $categoryRepository;
        $this->search = $search;
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
//        $tricks = $this->trickRepository->findBySearchQuery($searchTerm);
        $tricks = $this->search->searchTricks($searchTerm);
        return $this->render('trick/search.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
            'categoryId' =>"",
            'searchTerm' =>$searchTerm,
        ]);

    }


}
