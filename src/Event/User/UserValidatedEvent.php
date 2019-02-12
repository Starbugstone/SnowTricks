<?php

namespace App\Event\User;

use App\Event\AppEvent;

class UserValidatedEvent extends AppEvent
{
    const NAME = 'user.validated';
}