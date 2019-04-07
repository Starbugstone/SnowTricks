<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Event\Image\ImageAddEvent;
use App\Event\Image\ImageAddToNewTrickEvent;
use App\Event\Trick\TrickCreatedEvent;
use App\Form\ImageTypeForm;
use App\Form\TrickTypeForm;
use App\Form\VideoTypeForm;
use App\Repository\TagRepository;
use App\Serializer\TagSerializer;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EventDispatcherInterface $dispatcher, TagRepository $tagRepository, TagSerializer $tagSerializer, EntityManagerInterface $em)
    {
        $this->dispatcher = $dispatcher;
        $this->tagRepository = $tagRepository;
        $this->tagSerializer = $tagSerializer;
        $this->em = $em;
    }

    /**
     * @Route("/trick/new", name="trick.new")
     */
    public function new(Request $request)
    {
        $trick = new Trick();
        $image = new Image();
        $video = new Video();




        $imageForm = $this->createForm(ImageTypeForm::class, $image);

        $imageForm->handleRequest($request);

        if($imageForm->isSubmitted() && $imageForm->isValid()){

//            $event = new ImageAddToNewTrickEvent($image, $trick);
//            $this->dispatcher->dispatch(ImageAddToNewTrickEvent::NAME, $event);

            $this->em->persist($image);
            $this->em->flush();
            $trick->addImage($image);
        }


        $videoForm = $this->createForm(VideoTypeForm::class, $video);

        $videoForm->handleRequest($request);

        if($videoForm->isSubmitted() && $videoForm->isValid()){
            dd('here video');
        }


        //need to create the trick form at the end to get all the updates from the other adds
        $form = $this->createForm(TrickTypeForm::class, $trick, [
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

        dump($image);
        dump($video);
        dump($trick);
        dump($form);

        return $this->render('trick/admin/new.html.twig', [
            'form' => $form->createView(),
            'imageForm' => $imageForm->createView(),
            'videoForm' => $videoForm->createView(),
            'trick' => $trick,
        ]);
    }

}