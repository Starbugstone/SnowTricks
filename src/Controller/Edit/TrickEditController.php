<?php

namespace App\Controller\Edit;

use App\Entity\Trick;
use App\Event\Trick\TrickCreatedEvent;
use App\Event\Trick\TrickDeletedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\Form\TrickType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
class TrickEditController extends AbstractController
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

            $event = new TrickCreatedEvent($trick);
            $this->dispatcher->dispatch(TrickCreatedEvent::NAME, $event);

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
                    'onclick' => 'return confirm(\'are you sure?\')',
                ]
            ]);

        $form->handleRequest($request);


        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'delete') {

            $event = new TrickDeletedEvent($trick);
            $this->dispatcher->dispatch(TrickDeletedEvent::NAME, $event);

            return $this->redirectToRoute('trick.home');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $event = new TrickEditedEvent($trick);
            $this->dispatcher->dispatch(TrickEditedEvent::NAME, $event);

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
        $event = new TrickDeletedEvent($trick);
        $this->dispatcher->dispatch(TrickDeletedEvent::NAME, $event);

        return $this->redirectToRoute('trick.home');
    }


}