<?php

namespace App\EventSubscriber\Comment;

use App\Entity\Comment;
use App\Event\Comment\CommentDeletedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentDeletedSubscriber extends AbstractCommentSubscriber implements EventSubscriberInterface
{
    /**
     * Deletes a comment
     * @param CommentDeletedEvent $event
     */
    public function deleteCommentFromDatabase(CommentDeletedEvent $event)
    {
        $this->deleteFromDatabase($event);
        $this->addFlashMessage(FlashMessageCategory::SUCCESS, 'Comment Deleted');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            CommentDeletedEvent::NAME => 'deleteCommentFromDatabase'
        ];
    }
}