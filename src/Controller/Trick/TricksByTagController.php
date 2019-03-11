<?php

namespace App\Controller\Trick;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TricksByTagController extends AbstractController{

    /**
     * @Route("/trick/tag/{id}-{slug}", name="trick.tag", methods={"GET"})
     * @param Tag $tag
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showTricksByTag(Tag $tag, string $slug){

        if($tag->getSlug() !== $slug){
            return $this->redirectToRoute('trick.tag', [
                'id' => $tag->getId(),
                'slug' => $tag->getSlug()
            ], 301);
        }

        $tricks = $tag->getTrick();
//        dd($tag);

        return $this->render('trick/tag.html.twig', [
            'tag' => $tag,
        ]);
    }
}