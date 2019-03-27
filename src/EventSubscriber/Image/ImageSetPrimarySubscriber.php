<?php

namespace App\EventSubscriber\Image;

use App\Entity\Image;
use App\Entity\Trick;
use App\Event\Image\ImageSetPrimaryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageSetPrimarySubscriber extends ImageSubscriber implements EventSubscriberInterface
{
    /**
     * @var Trick $sentTrick
     */
    private $sentTrick;

    /**
     * @var Image $sentImage
     */
    private $sentImage;

    public function registerSentItems(ImageSetPrimaryEvent $event)
    {
        $this->sentTrick = $event->getTrick();
        $this->sentImage = $event->getEntity();
    }


    public function resetPrimaryImages(ImageSetPrimaryEvent $event)
    {
        if (!getenv('PRIMARY_IMAGE_CAROUSEL')) {
            $trick = $event->getTrick();

            //we only want one front image, so reset others. we could just reset the primary but this corrects bugs if we have 2 primary images for some unknown reason
            $trickImages = $trick->getImages();

            /** @var Image $trickImage */
            foreach ($trickImages as $trickImage) {
                if ($trickImage->getPrimaryImage()) {
                    $trickImage->setPrimaryImage(false);
                }
            }
            $this->em->persist($trick);
        }

    }

    public function setPrimaryImageToggle(ImageSetPrimaryEvent $event)
    {

        $trick = $event->getTrick();
        $image = $event->getEntity();

        $trickImages = $trick->getImages();
        $actualPrimaryImage = $this->sentTrick->getPrimaryImages()[0];

        //setting the actual image, if we clicked on the same image then unset
        /** @var Image $trickImage */
        foreach ($trickImages as $trickImage) {
            if ($trickImage === $image && $actualPrimaryImage !== $image) {
                $trickImage->setPrimaryImage(true);
            }
        }


    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ImageSetPrimaryEvent::NAME => [
                ['registerSentItems', 80],
                ['resetPrimaryImages', 50],
                ['setPrimaryImageToggle', 40],
                ['flush', 20],
            ],
        ];
    }


}