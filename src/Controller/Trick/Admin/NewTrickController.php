<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\Event\Trick\TrickCreatedEvent;
use App\Form\TrickFormType;
use App\Repository\TagRepository;
use App\Serializer\TagSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
class NewTrickController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
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
        TagRepository $tagRepository,
        TagSerializer $tagSerializer
    ) {
        $this->dispatcher = $dispatcher;
        $this->tagRepository = $tagRepository;
        $this->tagSerializer = $tagSerializer;
    }

    /**
     * @Route("/trick/new", name="trick.new")
     */
    public function new(Request $request)
    {
        $trick = new Trick();

        $form = $this->createForm(TrickFormType::class, $trick, [
            'all_tags_json' => $this->tagSerializer->allTagsJson(),
            'trick_tags_json' => $trick->getTagsJson(),
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
            'trick' => $trick,
        ]);
    }

}