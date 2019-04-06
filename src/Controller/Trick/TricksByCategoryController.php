<?php

namespace App\Controller\Trick;

use App\Entity\Category;
use App\Entity\Trick;
use App\Pagination\PagePagination;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    /**
     * @var PagePagination
     */
    private $pagePagination;

    public function __construct(TrickRepository $trickRepository, CategoryRepository $categoryRepository, PagePagination $pagePagination)
    {
        $this->trickRepository = $trickRepository;
        $this->categoryRepository = $categoryRepository;
        $this->pagePagination = $pagePagination;
    }

    /**
     * @Route("/trick/category/{categoryId}-{slug}", name="trick.search", methods={"GET"})
     * @param string $categoryId
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * show tricks in category
     */
    public function search(Request $request, $categoryId = "", $slug = "")
    {
        if ($categoryId !== "") {
            $category = $this->categoryRepository->find($categoryId);
            if ($category->getSlug() !== $slug) {
                return $this->redirectToRoute('trick.search', [
                    'id' => $category->getId(),
                    'slug' => $category->getSlug()
                ], 301);
            }
        }

        $page = $request->get('page') ?? 1;

        /** @var Paginator $tricks */
        $tricks = $this->trickRepository->findLatestEdited($page, (int)$categoryId);

        $nextPage = $this->pagePagination->nextPage($tricks, $page, Trick::NUMBER_OF_DISPLAYED_TRICKS);

        $categories = $this->categoryRepository->findAll();

        if ($request->isXmlHttpRequest()) {
            $render = $this->renderView('trick/_trick-card.html.twig', [
                'tricks' => $tricks,
            ]);
            $jsonResponse = array(
                'render' => $render,
                'nextPage' => $nextPage,
                'nextPageUrl' => $this->generateUrl(
                    'trick.search',
                    array(
                        'page' => $nextPage,
                        'categoryId' => $categoryId,
                        'slug' => $slug,
                        )
                ),
            );

            return new JsonResponse($jsonResponse);
        }

        return $this->render('trick/category.html.twig', [
            'tricks' => $tricks,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'slug' => $slug,
            'page' => $page,
            'nextPage' => $nextPage,
        ]);
    }
}