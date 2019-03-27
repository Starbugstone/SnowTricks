<?php

namespace App\EventSubscriber\Image;

use App\Entity\Image;
use App\Event\Image\ImageSetPrimaryEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageSetPrimarySubscriber extends ImageSubscriber implements EventSubscriberInterface
{

    public function resetPrimaryImages(ImageSetPrimaryEvent $event)
    {
        if (getenv('PRIMARY_IMAGE_CAROUSEL') === "false") {
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

        //setting the actual image, if we clicked on the same image then unset
        /** @var Image $trickImage */
        foreach ($trickImages as $trickImage) {
            if ($trickImage === $image) {
                if($trickImage->getPrimaryImage()){
                    $trickImage->setPrimaryImage(false);
                }else{
                    $trickImage->setPrimaryImage(true);
                }

            }
            $this->em->persist($trick);
        }


    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            ImageSetPrimaryEvent::NAME => [
                ['resetPrimaryImages', 50],
                ['setPrimaryImageToggle', 40],
                ['flush', 20],
            ],
        ];
    }


}