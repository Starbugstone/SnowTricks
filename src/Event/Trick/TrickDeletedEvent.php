<?php

namespace App\Event\Trick;

use App\Event\AppEvent;

class TrickDeletedEvent extends AppEvent
{
    const NAME = 'trick.deleted';

}