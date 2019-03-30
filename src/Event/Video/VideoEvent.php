<?php

namespace App\Event\Video;

use App\Entity\AppEntity;

use App\Entity\Trick;
use App\Entity\Video;
use App\Event\AppEvent;

abstract class VideoEvent extends AppEvent
{
    const NAME = 'user.defineMe';

    /**
     * @var Video
     */
    protected $entity;

    /**
     * @var Trick
     */
    protected $trick;

    public function __construct(Video $image, Trick $trick)
    {
        $this->entity = $image;
        $this->trick = $trick;
    }

    /**
     * @return Video
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