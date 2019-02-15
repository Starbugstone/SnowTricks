<?php

namespace App\Event;

use App\Entity\AppEntity;
use Symfony\Component\EventDispatcher\Event;

abstract class AppEvent extends Event
{
    const NAME = 'defineMe';

    /**
     * @var AppEntity
     */
    protected $entity;

    public function __construct(AppEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return AppEntity
     */
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }
}