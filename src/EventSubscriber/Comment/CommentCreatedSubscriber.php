<?php

namespace App\EventSubscriber\Comment;

use App\Event\Comment\CommentCreatedEvent;
use App\Event\Comment\CommentEditedEvent;
use App\Event\Comment\AbstractCommentEvent;
use App\FlashMessage\FlashMessageCategory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentCreatedSubscriber extends AbstractCommentSubscriber implements EventSubscriberInterface
{
    /**
     * Send Comment to the database and set a flash message
     * @param AbstractCommentEvent $event
     */
    public function registerCommentToDatabase(AbstractCommentEvent $event)
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