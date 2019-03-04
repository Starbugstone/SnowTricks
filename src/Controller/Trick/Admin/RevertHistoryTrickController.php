<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\History\TrickHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class RevertHistoryTrickController
 * @package App\Controller\Trick\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class RevertHistoryTrickController extends AbstractController
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
     * @param $id
     * @param $historyId
     * @Route("/trick/revert/{id}/{historyId}", name="trick.revert")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function revertHistory(Trick $trick, $historyId)
    {
        $this->trickHistory->revertToHistory($trick->getId(), $historyId);

        return $this->redirectToRoute('trick.show', [
            'id' => $trick->getId(),
            'slug' => $trick->getSlug(),
        ]);
    }
}