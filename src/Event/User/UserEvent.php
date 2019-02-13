<?php

namespace App\Event\User;

use App\Entity\AppEntity;
use App\Entity\User;
use App\Event\AppEvent;

abstract class UserEvent extends AppEvent
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
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }
}