<?php

namespace App\Event\User;

use App\Event\AppEvent;

class UserForgotpasswordEvent extends AppEvent
{
    const NAME = 'user.forgotpassword';

}