<?php

namespace App\EventSubscriber\Comment;

use App\Entity\Comment;
use App\Event\Comment\CommentDeletedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentDeletedSubscriber extends CommentSubscriber implements EventSubscriberInterface
{
    /**
     * Deletes a comment
     * @param CommentDeletedEvent $event
     */
    public function deleteCommentFromDatabase(CommentDeletedEvent $event)
    {
        /** @var Comment $comment */
        $comment = $event->getEntity();
        $this->deleteFromDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Comment Deleted');
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