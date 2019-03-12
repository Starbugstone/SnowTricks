<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateCommentController
 * @package App\Controller\Comment
 * @IsGranted("ROLE_USER")
 */
class CreateCommentController extends AbstractController{


    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/comment/add/{id}", name="comment.create")
     */
    public function createComment(Trick $trick){
        $comment = new Comment();

        //Set the user to the current logged in user
        $comment->setUser($this->getUser());
        $comment->setTrick($trick);
        $comment->setComment("Bla Bla Bla");

        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectToRoute('trick.show', ['id'=>$trick->getId(), 'slug'=>$trick->getSlug()]);

    }

}