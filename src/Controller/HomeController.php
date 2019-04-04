<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
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
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $page = $request->get('page')??1;

        /** @var Paginator $tricks */
        $tricks = $this->repository->findLatestEdited($page);
        $totalTricks = $tricks->count();

        if ($page > ceil($totalTricks / Trick::NUMBER_OF_DISPLAYED_TRICKS)) {
            throw new NotFoundHttpException("Page does not exist");
        }

        $nextPage = 0;
        if (!($page + 1 > ceil($totalTricks / Trick::NUMBER_OF_DISPLAYED_TRICKS))) {
            $nextPage = $page + 1;
        }

        if ($request->isXmlHttpRequest()){
            $render = $this->renderView('trick/_trick-card.html.twig', [
                'tricks' => $tricks,
            ]);
            $jsonResponse = array(
                'render' => $render,
                'nextPage' => $nextPage,
                'nextPageUrl' => $this->generateUrl('home', array('page' => $nextPage)),
            );

            return new JsonResponse($jsonResponse);
        }

            return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
            'totalTricks' => $totalTricks,
            'page' => $page,
            'nextPage' => $nextPage,
        ]);
    }
}