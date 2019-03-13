<?php

namespace App\Event\Comment;

use App\Entity\AppEntity;
use App\Entity\Comment;
use App\Event\AppEvent;

abstract class CommentEvent extends AppEvent
{
    const NAME = 'user.defineMe';

    /**
     * @var Comment
     */
    protected $entity;

    public function __construct(Comment $comment)
    {
        $this->entity = $comment;
    }

    /**
     * @return Comment
     */
    public function getEntity(): AppEntity
    {
        return $this->entity;
    }
}