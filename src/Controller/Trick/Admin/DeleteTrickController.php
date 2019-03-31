<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\Event\Trick\TrickDeletedEvent;
use App\Exception\RedirectException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TrickEditController
 * @package App\Controller\Edit
 *
 * Require the user to be connected for everything here
 * @IsGranted("ROLE_USER")
 */
class DeleteTrickController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/trick/delete/{id}", name="trick.delete", methods={"POST"})
     * @param Trick $trick
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Trick $trick, Request $request)
    {

        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-trick' . $trick->getId(), $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $event = new TrickDeletedEvent($trick);
        $this->dispatcher->dispatch(TrickDeletedEvent::NAME, $event);

        return $this->redirectToRoute('home');

    }


}