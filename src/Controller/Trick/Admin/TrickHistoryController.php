<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\History\TrickHistory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickHistoryController
 * @package App\Controller\Trick\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class TrickHistoryController extends AbstractController
{

    /**
     * @var TrickHistory
     */
    private $trickHistory;

    public function __construct(TrickHistory $trickHistory)
    {
        $this->trickHistory = $trickHistory;
    }

    /**
     * @Route("/trick/edit/history/{id}", name="trick.history")
     * @return Response
     */
    public function index(Trick $trick)
    {

        $history = $this->trickHistory->getHistory($trick->getId());

        return $this->render('trick/admin/trick-history.html.twig', [
            'trick' => $trick,
            'history' => $history,
        ]);
    }
}
