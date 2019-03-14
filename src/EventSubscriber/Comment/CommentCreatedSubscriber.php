<?php

namespace App\EventSubscriber\Comment;

use App\Event\Comment\CommentCreatedEvent;
use App\Event\Comment\CommentEditedEvent;
use App\Event\Comment\CommentEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentCreatedSubscriber extends CommentSubscriber implements EventSubscriberInterface
{
    /**
     * Send Comment to the database and set a flash message
     * @param CommentEvent $event
     */
    public function registerCommentToDatabase(CommentEvent $event)
    {
        $this->sendToDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Comment saved');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            CommentCreatedEvent::NAME => 'registerCommentToDatabase',
            CommentEditedEvent::NAME => 'registerCommentToDatabase',
        ];
    }
}