<?php

namespace App\Controller\Image;

use App\Entity\Image;
use App\Event\Image\ImageDeleteEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteImageController
 * @package App\Controller\Image
 * @IsGranted("ROLE_USER")
 */
class DeleteImageController extends AbstractController
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
     * @Route("/image/delete/{id}", name="image.deleteFromTrick", methods={"POST"})
     * @param Image $image
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrickImage(Image $image)
    {
        $trick = $image->getTrick();
        $event = new ImageDeleteEvent($image, $image->getTrick());
        $this->dispatcher->dispatch(ImageDeleteEvent::NAME, $event);

        return $this->redirectToRoute('trick.edit', [
            'id' => $trick->getId(),
        ]);
    }
}