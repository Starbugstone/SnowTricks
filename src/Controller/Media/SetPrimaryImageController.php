<?php

namespace App\Controller\Media;

use App\Entity\Image;
use App\Entity\Trick;
use App\Event\Image\ImageSetPrimaryEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @return JsonResponse|RedirectResponse
     */
    public function setPrimaryController(Trick $trick, Image $image, Request $request)
    {
        $event = new ImageSetPrimaryEvent($image, $trick);
        $this->dispatcher->dispatch(ImageSetPrimaryEvent::NAME, $event);

        if ($request->isXmlHttpRequest()) {
            $jsonResponse = array(
                'id' => $image->getId(),
                'image' => getenv('DEFAULT_UPLOAD_TRICK_IMAGE_PATH').'/'.$image->getImage(),
                'isPrimary' => $image->getPrimaryImage(),
                'defaultPrimaryImage' => getenv('DEFAULT_IMAGE_PATH').'/'.getenv('DEFAULT_TRICK_IMAGE'),
                'isCarousel' => getenv('PRIMARY_IMAGE_CAROUSEL'),
            );

            return new JsonResponse($jsonResponse);
        }

        return $this->redirectToRoute('trick.edit', ['id' => $trick->getId()]);


    }

}