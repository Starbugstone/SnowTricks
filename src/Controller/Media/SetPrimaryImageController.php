<?php

namespace App\Controller\Media;

use App\Entity\Image;
use App\Entity\Trick;
use App\Event\Image\ImageSetPrimaryEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SetPrimaryImageController
 * @package App\Controller\Image
 *
 * Require the user to be connected for everything here
 * @IsGranted("ROLE_USER")
 */
class SetPrimaryImageController extends AbstractController
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
     * @Route("/image/set/{trick}-{image}", name="image.setprimary")
     * @param Trick $trick
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setPrimaryController(Trick $trick, Image $image)
    {
        $event = new ImageSetPrimaryEvent($image, $trick);
        $this->dispatcher->dispatch(ImageSetPrimaryEvent::NAME, $event);

        return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);
    }

}