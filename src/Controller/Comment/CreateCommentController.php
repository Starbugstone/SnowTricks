<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Event\Comment\CommentCreatedEvent;
use App\Exception\RedirectException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateCommentController
 * @package App\Controller\Comment
 * @IsGranted("ROLE_USER")
 */
class CreateCommentController extends AbstractController
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
     * @Route("/comment/add/{id}", name="comment.create", methods={"POST"})
     */
    public function createComment(Trick $trick, Request $request)
    {

        $receivedComment = $request->request->get('comment_type_form');

        if (!$this->isCsrfTokenValid('CommentForm', $receivedComment['_token'])) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $comment = new Comment();

        //Set the user to the current logged in user
        $comment->setUser($this->getUser());
        $comment->setTrick($trick);
        $comment->setComment($receivedComment['comment']);

        $event = new CommentCreatedEvent($comment);
        $this->dispatcher->dispatch(CommentCreatedEvent::NAME, $event);

        return $this->redirectToRoute('trick.show', [
            'id' => $trick->getId(),
            'slug' => $trick->getSlug(),
            '_fragment' => 'comment-'.$comment->getId(),
        ]);

    }

}