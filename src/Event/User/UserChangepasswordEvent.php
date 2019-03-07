<?php

namespace App\Event\User;

use App\Entity\User;

class UserChangepasswordEvent extends UserPasswordEvent
{
    const NAME = 'user.changepassword';

}