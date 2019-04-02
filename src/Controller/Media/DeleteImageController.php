<?php

namespace App\Controller\Media;

use App\Entity\Image;
use App\Event\Image\ImageDeleteEvent;
use App\Exception\RedirectException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrickImage(Image $image, Request $request)
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-image' . $image->getId(), $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $trick = $image->getTrick();
        if ($trick !== null){
            $event = new ImageDeleteEvent($image, $trick);
            $this->dispatcher->dispatch(ImageDeleteEvent::NAME, $event);
        }

        return $this->redirectToRoute('trick.edit', [
            'id' => $trick->getId(),
        ]);
    }
}