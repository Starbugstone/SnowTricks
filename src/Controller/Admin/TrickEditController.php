<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickEditController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    private function deleteTrick(Trick $trick)
    {
        $this->em->remove($trick);
        $this->em->flush();
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/trick/new", name="trick.new")
     */
    public function new(Request $request)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => [
                'class' => 'waves-effect waves-light btn right'
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($trick);
            $this->em->flush();
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('trick/admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{id}/edit", name="trick.edit")
     */
    public function edit(Trick $trick, Request $request)
    {

        $form = $this->createForm(TrickType::class, $trick);
        $form
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2'
                ]
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'Delete',
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2',
                    'onclick' =>'return confirm(\'are you sure?\')',
                ]
            ]);

        $form->handleRequest($request);


        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'delete') {
            $this->deleteTrick($trick);
            return $this->redirectToRoute('trick.home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute('trick.show', [
                'id' => $trick->getId(),
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('trick/admin/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/trick/{id}/delete", name="trick.delete")
     */
    public function delete(Trick $trick)
    {
        $this->deleteTrick($trick);
        return $this->redirectToRoute('trick.home');
    }


}