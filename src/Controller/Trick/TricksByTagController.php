<?php

namespace App\Controller\Trick;

use App\Entity\Tag;
use App\Entity\Trick;
use App\Pagination\PagePagination;
use App\Repository\TrickRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksByTagController extends AbstractController
{

    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var PagePagination
     */
    private $pagePagination;

    public function __construct(TrickRepository $trickRepository, PagePagination $pagePagination)
    {
        $this->trickRepository = $trickRepository;
        $this->pagePagination = $pagePagination;
    }

    /**
     * @Route("/trick/tag/{id}-{slug}", name="trick.tag", methods={"GET"})
     * @param Tag $tag
     * @param string $slug
     * @return RedirectResponse|Response
     */
    public function showTricksByTag(Request $request, Tag $tag, string $slug)
    {
        if ($tag->getSlug() !== $slug) {
            return $this->correctSlug($tag);
        }

        $page = $request->get('page') ?? 1;

        /** @var Paginator $tricks */
        $tricks = $this->trickRepository->findLatestEditedByTag($page, $tag->getId());

        $nextPage = $this->pagePagination->nextPage($tricks, $page, Trick::NUMBER_OF_DISPLAYED_TRICKS);

        if ($request->isXmlHttpRequest()) {
            $render = $this->renderView('trick/_trick-card.html.twig', [
                'tricks' => $tricks,
            ]);
            $jsonResponse = array(
                'render' => $render,
                'nextPage' => $nextPage,
                'nextPageUrl' => $this->generateUrl(
                    'trick.tag',
                    array(
                        'page' => $nextPage,
                        'id' => $tag->getId(),
                        'slug' => $slug,
                    )
                ),
            );

            return new JsonResponse($jsonResponse);
        }

        return $this->render('trick/tag.html.twig', [
            'tag' => $tag,
            'tricks' => $tricks,
            'tagId' => $tag->getId(),
            'slug' => $slug,
            'page' => $page,
            'nextPage' => $nextPage,
        ]);
    }

    /**
     * @Route("/trick/tag/{id}", name="trick.tag.id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function tagOnlyId(Tag $tag)
    {
        return $this->correctSlug($tag);
    }

    /**
     * Redirecting to the correct url
     * @param Tag $tag
     * @return RedirectResponse
     */
    private function correctSlug(Tag $tag)
    {
        return $this->redirectToRoute('trick.tag', [
            'id' => $tag->getId(),
            'slug' => $tag->getSlug()
        ], 301);
    }
}