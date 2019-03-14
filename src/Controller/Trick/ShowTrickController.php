<?php

namespace App\Controller\Trick;

use App\Entity\Trick;
use App\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShowTrickController extends AbstractController
{

    /**
     * @Route("/trick/{id}-{slug}", name="trick.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show(Trick $trick, string $slug)
    {

        //Checking if slug is equal to the ID. This is for SEO and external links
        if ($trick->getSlug() !== $slug) {
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug()
            ], 301);
        }
        $commentForm = $this->createForm(CommentType::class);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
        ]);
    }

}
