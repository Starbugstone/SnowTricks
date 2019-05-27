<?php

namespace App\Controller\Trick;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Pagination\PagePagination;
use App\Repository\CommentRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{

    /**
     * @var CommentRepository
     */
    private $repository;
    /**
     * @var PagePagination
     */
    private $pagePagination;

    public function __construct(CommentRepository $repository, PagePagination $pagePagination)
    {
        $this->repository = $repository;
        $this->pagePagination = $pagePagination;
    }

    /**
     * @Route("/trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Trick $trick, string $slug, Request $request)
    {
        //Checking if slug is equal to the ID. This is for SEO and external links
        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ], 301);
        }

        $commentForm = $this->createForm(CommentFormType::class);

        $page = $request->get('page') ?? 1;

        /** @var Paginator $tricks */
        $comments = $this->repository->findLatestEdited($trick->getId(), $page);

        $nextPage = $this->pagePagination->nextPage($comments, $page, Comment::NUMBER_OF_DISPLAYED_COMMENTS);

        if ($request->isXmlHttpRequest()) {
            $render = $this->renderView('comment/_comment-line.html.twig', [
                'comments' => $comments,
            ]);
            $jsonResponse = array(
                'render' => $render,
                'nextPage' => $nextPage,
                'nextPageUrl' => $this->generateUrl('trick.show',
                    array('page' => $nextPage, 'id' => $trick->getId(), 'slug' => $trick->getSlug())),
            );

            return new JsonResponse($jsonResponse);
        }


        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
            'actionPath' => $this->generateUrl('comment.create', ['id' => $trick->getId()]),
            'comments' => $comments,
            'nextPage' => $nextPage,
        ]);
    }


    /**
     * Adding a route with only the ID that redirects to the slugged route
     * @Route("/trick/{id}", name="trick.show.id")
     */
    public function showOnlyId(Trick $trick)
    {

        return $this->redirectToRoute('trick.show', [
            'id' => $trick->getId(),
            'slug' => $trick->getSlug()
        ], 301);
    }

}
