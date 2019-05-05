<?php

namespace App\Event\Trick;

use App\Entity\AbstractAppEntity;
use App\Entity\Trick;
use App\Event\AbstractAppEvent;

abstract class AbstractTrickEvent extends AbstractAppEvent
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
    public function getEntity(): AbstractAppEntity
    {
        return $this->entity;
    }
}