<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Event\Comment\CommentEditedEvent;
use App\Exception\RedirectException;
use App\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EditCommentController
 * @package App\Controller\Comment
 * @IsGranted("ROLE_USER")
 */
class EditCommentController extends AbstractController
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
     * @Route("/comment/edit/{id}", name="comment.edit", methods={"GET"})
     */
    public function editComment(Comment $comment)
    {
        $this->checkSecurity($comment);
        $commentForm = $this->createForm(CommentType::class, $comment, [
            'save_button_label' => 'Update',
        ]);

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'commentForm' => $commentForm->createView(),
        ]);

    }

    /**
     * @Route("/comment/edit/{id}", name="comment.submit", methods={"POST"})
     */
    public function editCommentSubmit(Comment $comment, Request $request)
    {
        $this->checkSecurity($comment);

        $form = $this->createForm(CommentType::class, $comment, [
            'save_button_label' => 'Update',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new CommentEditedEvent($comment);
            $this->dispatcher->dispatch(CommentEditedEvent::NAME, $event);

            return $this->redirectToRoute('trick.show', [
                'id' => $comment->getTrick()->getId(),
                'slug' => $comment->getTrick()->getSlug(),
                '_fragment' => 'comment-'.$comment->getId(),
            ]);
        }

        //This should never be called unless we have some strange error
        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'commentForm' => $form->createView(),
        ]);

    }

    /**
     * @param Comment $comment
     * Checks if the user is admin or author of the comment.
     * Thows a redirect to the trick show page
     */
    private function checkSecurity(Comment $comment){
        if(!($this->isGranted('ROLE_ADMIN') || $this->getUser()->getId() === $comment->getUser()->getId()))
        {
            Throw new RedirectException($this->generateUrl('trick.show', ['id'=> $comment->getTrick()->getId(), 'slug'=> $comment->getTrick()->getSlug()]),"You are not allowed to edit this comment");
        }
    }


}