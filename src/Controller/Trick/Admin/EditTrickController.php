<?php

namespace App\Controller\Trick\Admin;


use App\Entity\Trick;
use App\Event\Trick\TrickAddPrimaryImageEvent;
use App\Event\Trick\TrickDeletedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\Form\TrickFormType;
use App\History\TrickHistory;
use App\Repository\TagRepository;
use App\Serializer\TagSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
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
    /**
     * @var TagSerializer
     */
    private $tagSerializer;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TrickHistory $trickHistory,
        TagRepository $tagRepository,
        TagSerializer $tagSerializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->trickHistory = $trickHistory;
        $this->tagRepository = $tagRepository;
        $this->tagSerializer = $tagSerializer;
    }

    /**
     * @Route("/trick/edit/{id}", name="trick.edit")
     */
    public function edit(Trick $trick, Request $request)
    {
        $originalTrickImages = $trick->getImages()->count();
        /** @var Form $form */
        $form = $this->createForm(TrickFormType::class, $trick, [
            'all_tags_json' => $this->tagSerializer->allTagsJson(),
            'trick_tags_json' => $trick->getTagsJson(),
        ]);

        $form->handleRequest($request);

        if ($form->getClickedButton() && $form->getClickedButton()->getName() === 'delete') {

            $event = new TrickDeletedEvent($trick);
            $this->dispatcher->dispatch(TrickDeletedEvent::NAME, $event);

            return $this->redirectToRoute('home');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if($originalTrickImages === 0){
                $addImageEvent = new TrickAddPrimaryImageEvent($trick);
                $this->dispatcher->dispatch(TrickAddPrimaryImageEvent::NAME, $addImageEvent);
            }
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

}