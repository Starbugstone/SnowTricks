<?php

namespace App\Controller\Trick;

use App\Entity\Category;
use App\Entity\Trick;
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
        $totalTricks = $tricks->count(); //TODO: this is duplicate of HomeController, refactor

        if ($page > ceil($totalTricks / Trick::NUMBER_OF_DISPLAYED_TRICKS)) {
            throw new NotFoundHttpException("Page does not exist");
        }

        $nextPage = 0;
        if (!($page + 1 > ceil($totalTricks / Trick::NUMBER_OF_DISPLAYED_TRICKS))) {
            $nextPage = $page + 1;
        }

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
            'totalTricks' => $totalTricks,
            'page' => $page,
            'nextPage' => $nextPage,
        ]);
    }
}