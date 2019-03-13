<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Event\Comment\CommentEditedEvent;
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
        //TODO: Check for security, the user can only edit there own comments. Admins can edit all
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
        //TODO: Check for security, the user can only edit there own comments. Admins can edit all

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new CommentEditedEvent($comment);
            $this->dispatcher->dispatch(CommentEditedEvent::NAME, $event);
//            dd($comment);
            return $this->redirectToRoute('trick.show', [
                'id' => $comment->getTrick()->getId(),
                'slug' => $comment->getTrick()->getSlug(),
            ]);
        }


        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'commentForm' => $commentForm->createView(),
        ]);

    }


}