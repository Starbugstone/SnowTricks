<?php

namespace App\Event\User;


class UserValidatedEvent extends AbstractUserEvent
{
    const NAME = 'user.validated';
}