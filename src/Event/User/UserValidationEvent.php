<?php

namespace App\Event\User;

use App\Entity\AppEntity;
use App\Event\AppEvent;

class UserValidationEvent extends AppEvent
{
    const NAME = 'user.validation';
    /**
     * @var string
     */
    private $token;


    public function __construct(AppEntity $entity, string $token)
    {
        parent::__construct($entity);

        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }


}