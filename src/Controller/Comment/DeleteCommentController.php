<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Event\Comment\CommentDeletedEvent;
use App\Exception\RedirectException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteCommentController
 * @package App\Controller\Comment
 * @IsGranted("ROLE_USER")
 */
class DeleteCommentController extends AbstractController
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
     * @Route("/comment/delete/{id}", name="comment.delete", methods={"GET"})
     */
    public function deleteTrick(Comment $comment)
    {
        if(!($this->isGranted('ROLE_ADMIN') || $this->getUser()->getId() === $comment->getUser()->getId()))
        {
            Throw new RedirectException($this->generateUrl('trick.show', ['id'=> $comment->getTrick()->getId(), 'slug'=> $comment->getTrick()->getSlug()]),"You are not allowed to edit this comment");
        }

        $trick = $comment->getTrick();

        $event = new CommentDeletedEvent($comment);
        $this->dispatcher->dispatch(CommentDeletedEvent::NAME, $event);

        return $this->redirectToRoute('trick.show',['id'=> $trick->getId(), 'slug' => $trick->getSlug()]);
    }
}