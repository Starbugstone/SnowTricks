<?php

namespace App\Event;

use App\Entity\AbstractAppEntity;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractAppEvent extends Event
{
    const NAME = 'defineMe';

    /**
     * @var AbstractAppEntity
     */
    protected $entity;

    public function __construct(AbstractAppEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return AbstractAppEntity
     */
    public function getEntity(): AbstractAppEntity
    {
        return $this->entity;
    }
}