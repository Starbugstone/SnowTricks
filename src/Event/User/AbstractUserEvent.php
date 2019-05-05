<?php

namespace App\Event\User;

use App\Entity\AbstractAppEntity;
use App\Entity\User;
use App\Event\AbstractAppEvent;

abstract class AbstractUserEvent extends AbstractAppEvent
{
    const NAME = 'user.defineMe';

    /**
     * @var User
     */
    protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    /**
     * @return User
     */
    public function getEntity(): AbstractAppEntity
    {
        return $this->entity;
    }
}