<?php

namespace App\Controller\Trick\Admin;


use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Event\Image\ImageAddEvent;
use App\Event\Trick\TrickDeletedEvent;
use App\Event\Trick\TrickEditedEvent;
use App\Event\Video\VideoAddEvent;
use App\Form\ImageTypeForm;
use App\Form\TrickTypeForm;
use App\Form\VideoTypeForm;
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
        /** @var Form $form */
        $form = $this->createForm(TrickTypeForm::class, $trick, [
            'all_tags_json' => $this->tagSerializer->allTagsJson(),
            'trick_tags_json' => $trick->getTagsJson(),
        ]);
        $form
            ->add('delete', SubmitType::class, [
                'label' => 'Delete',
                'attr' => [
                    'class' => 'waves-effect waves-light btn right mr-2',
                    'onclick' => 'return confirm(\'are you sure?\')',
                ]
            ]);

        $form->handleRequest($request);

        $trickImage = new Image();
        $imageForm = $this->createForm(ImageTypeForm::class, $trickImage);
        $imageForm->handleRequest($request);

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $event = new ImageAddEvent($trickImage, $trick);
            $this->dispatcher->dispatch(ImageAddEvent::NAME, $event);

            //Forcing the next form shown to be a new image
            $trickImage = new Image();
            $imageForm = $this->createForm(ImageTypeForm::class, $trickImage);
        }

        $trickVideo = new Video();

        $videoForm = $this->createForm(VideoTypeForm::class, $trickVideo);
        $videoForm->handleRequest($request);

        if($videoForm->isSubmitted() && $videoForm->isValid()){
//            dump($trick);
//            dump($trickVideo);
//            dd("TODO : video submitted ");

            $event = new VideoAddEvent($trickVideo, $trick);
            $this->dispatcher->dispatch(VideoAddEvent::NAME, $event);

            //resetting
            $trickVideo = new Video();
            $videoForm = $this->createForm(VideoTypeForm::class, $trickVideo);
        }


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
            'trick' => $trick,
            'form' => $form->createView(),
            'imageForm' => $imageForm->createView(),
            'videoForm' => $videoForm->createView(),
        ]);
    }

}