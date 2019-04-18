<?php

namespace App\Event\User;

class UserRegisteredEvent extends AbstractUserPasswordEvent
{
    const NAME = 'user.registered';

}