<?php

namespace App\Event;

use App\Entity\AppEntity;
use App\Entity\Trick;
use Symfony\Component\EventDispatcher\Event;

abstract class AppEvent extends Event
{
    const NAME = 'defineMe';

    /**
     * @var Trick
     */
    protected $entity;

    public function __construct(AppEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Trick
     */
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }
}