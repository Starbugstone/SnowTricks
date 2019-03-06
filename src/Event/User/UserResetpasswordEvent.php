<?php

namespace App\Event\User;

use App\Entity\User;

class UserResetpasswordEvent extends UserEvent
{
    const NAME = 'user.resetpassword';

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