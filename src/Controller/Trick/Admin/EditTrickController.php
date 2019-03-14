<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Tag;
use App\Entity\Trick;
use App\Event\Trick\TrickDeletedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\Form\TrickType;
use App\History\TrickHistory;
use App\Repository\TagRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
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
class EditTrickController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var TrickHistory
     */
    private $trickHistory;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(EventDispatcherInterface $dispatcher, TrickHistory $trickHistory, TagRepository $tagRepository )
    {
        $this->dispatcher = $dispatcher;
        $this->trickHistory = $trickHistory;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @Route("/trick/edit/{id}", name="trick.edit")
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

            return $this->redirectToRoute('home');
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
            'allTags' => $this->tagRepository->findAll(),
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

}