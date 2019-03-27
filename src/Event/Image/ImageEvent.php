<?php

namespace App\Event\Image;

use App\Entity\AppEntity;

use App\Entity\Image;
use App\Entity\Trick;
use App\Event\AppEvent;

abstract class ImageEvent extends AppEvent
{
    const NAME = 'user.defineMe';

    /**
     * @var Image
     */
    protected $entity;

    /**
     * @var Trick
     */
    protected $trick;

    public function __construct(Image $image, Trick $trick)
    {
        $this->entity = $image;
        $this->trick = $trick;
    }

    /**
     * @return Image
     */
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }

    public function getTrick(): Trick
    {
        return $this->trick;
    }
}