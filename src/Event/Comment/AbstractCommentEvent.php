<?php

namespace App\Event\Comment;

use App\Entity\AbstractAppEntity;
use App\Entity\Comment;
use App\Event\AbstractAppEvent;

abstract class AbstractCommentEvent extends AbstractAppEvent
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
    public function getEntity(): AbstractAppEntity
    {
        return $this->entity;
    }
}