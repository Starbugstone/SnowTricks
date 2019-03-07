<?php

namespace App\Event\User;

use App\Entity\User;

class UserResetpasswordEvent extends UserPasswordEvent
{
    const NAME = 'user.resetpassword';

}