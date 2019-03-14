<?php

namespace App\Event\Trick;

use App\Entity\AppEntity;

use App\Entity\Trick;
use App\Event\AppEvent;

abstract class TrickEvent extends AppEvent
{
    const NAME = 'user.defineMe';

    /**
     * @var Trick
     */
    protected $entity;

    public function __construct(Trick $trick)
    {
        $this->entity = $trick;
    }

    /**
     * @return Trick
     */
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }
}