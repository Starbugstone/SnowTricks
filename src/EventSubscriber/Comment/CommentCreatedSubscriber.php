<?php

namespace App\EventSubscriber\Comment;

use App\Event\Comment\CommentCreatedEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentCreatedSubscriber extends CommentSubscriber implements EventSubscriberInterface
{
    /**
     * Send Comment to the database and set a flash message
     * @param CommentCreatedEvent $event
     */
    public function registerCommentToDatabase(CommentCreatedEvent $event)
    {
        $this->sendToDatabase($event);
        $this->addFlash(FlashMessageCategory::SUCCESS, 'Comment posted');
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            CommentCreatedEvent::NAME => 'registerCommentToDatabase'
        ];
    }
}