<?php

namespace App\Controller\Media;

use App\Entity\Video;
use App\Event\Video\VideoDeleteEvent;
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
class DeleteVideoController extends AbstractController
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
     * @Route("/video/delete/{id}", name="video.deleteFromTrick", methods={"POST"})
     * @param Video $video
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrickImage(video $video, Request $request)
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete-video' . $video->getId(), $submittedToken)) {
            throw new RedirectException($this->generateUrl('home'), 'Bad CSRF Token');
        }

        $trick = $video->getTrick();
        if($trick !== null){
            $event = new VideoDeleteEvent($video, $video->getTrick());
            $this->dispatcher->dispatch(VideoDeleteEvent::NAME, $event);
        }


        return $this->redirectToRoute('trick.edit', [
            'id' => $trick->getId(),
        ]);
    }
}