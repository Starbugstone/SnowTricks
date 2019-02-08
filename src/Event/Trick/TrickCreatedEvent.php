<?php

namespace App\Event\Trick;

use App\Event\AppEvent;

class TrickCreatedEvent extends AppEvent
{
    const NAME = 'trick.created';

}