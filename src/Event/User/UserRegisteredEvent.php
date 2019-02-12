<?php

namespace App\Event\User;

use App\Entity\AppEntity;
use App\Event\AppEvent;

class UserRegisteredEvent extends AppEvent
{
    const NAME = 'user.registered';

    private $plainPassword;

    public function __construct(AppEntity $entity, string $plainPassword)
    {
        parent::__construct($entity);

        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}