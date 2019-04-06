<?php

namespace App\Controller\Trick;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentTypeForm;
use App\Repository\CommentRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{

    /**
     * @var CommentRepository
     */
    private $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
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

        $commentForm = $this->createForm(CommentTypeForm::class);

        $page = $request->get('page')??1;

        /** @var Paginator $tricks */
        $comments = $this->repository->findLatestEdited($trick->getId(), $page);
        $totalComments = $comments->count();

        if ($page > ceil($totalComments / Comment::NUMBER_OF_DISPLAYED_COMMENTS)) {
            throw new NotFoundHttpException("Page does not exist");
        }

        $nextPage = 0;
        if (!($page + 1 > ceil($totalComments / Comment::NUMBER_OF_DISPLAYED_COMMENTS))) {
            $nextPage = $page + 1;
        }

//        if ($request->isXmlHttpRequest()) {
//            $render = $this->renderView('trick/_trick-card.html.twig', [
//                'tricks' => $tricks,
//            ]);
//            $jsonResponse = array(
//                'render' => $render,
//                'nextPage' => $nextPage,
//                'nextPageUrl' => $this->generateUrl('home', array('page' => $nextPage)),
//            );
//
//            return new JsonResponse($jsonResponse);
//        }


        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
            'nextPage' => $nextPage,
        ]);
    }

}
