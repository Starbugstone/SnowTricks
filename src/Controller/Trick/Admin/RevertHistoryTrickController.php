<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\Exception\RedirectException;
use App\FlashMessage\AddFlashTrait;
use App\FlashMessage\FlashMessageCategory;
use App\History\TrickHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class RevertHistoryTrickController
 * @package App\Controller\Trick\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class RevertHistoryTrickController extends AbstractController
{
    use AddFlashTrait;

    /**
     * @var TrickHistory
     */
    private $trickHistory;

    public function __construct(TrickHistory $trickHistory)
    {
        $this->trickHistory = $trickHistory;
    }

    /**
     * @param Trick $trick
     * @param $historyId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/trick/revert/{id}/{historyId}", name="trick.revert")
     */
    public function revertHistory(Trick $trick, $historyId, Request $request)
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('revert-trick' . $historyId, $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $this->trickHistory->revertToHistory($trick->getId(), $historyId);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Reverted ' . $trick->getName());
        return $this->redirectToRoute('trick.show', [
            'id' => $trick->getId(),
            'slug' => $trick->getSlug(),
        ]);


    }
}