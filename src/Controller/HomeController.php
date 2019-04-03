<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $page = $request->get('page');

        if (!$page) {
            $page = 1;
        }

        if (!is_numeric($page) || $page < 1) {
            throw new \InvalidArgumentException("Page number is not valid");
        }

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



        return $this->render('trick/index.html.twig', [
            'tricks' => $tricks,
            'totalTricks' => $totalTricks,
            'page' => $page,
            'nextPage' => $nextPage,
        ]);
    }
}