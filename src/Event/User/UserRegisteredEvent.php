<?php

namespace App\Event\User;

class UserRegisteredEvent extends UserPasswordEvent
{
    const NAME = 'user.registered';

}