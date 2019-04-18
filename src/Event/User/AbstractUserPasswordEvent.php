<?php

namespace App\Event\User;

use App\Entity\User;

abstract class AbstractUserPasswordEvent extends AbstractUserEvent
{
    const NAME = 'user.defineMe';

    private $plainPassword;

    public function __construct(User $entity, string $plainPassword)
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