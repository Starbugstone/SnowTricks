<?php

namespace App\Event\Comment;

use App\Event\AppEvent;

class CommentCreatedEvent extends AppEvent
{
    const NAME = 'comment.created';

}