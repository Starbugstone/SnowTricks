<?php

namespace App\EventSubscriber;

use App\Event\AbstractAppEvent;
use App\FlashMessage\AddFlashTrait;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractAppSubscriber
{
    use AddFlashTrait;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @return array The event names to listen to
     */
    abstract public static function getSubscribedEvents();

    /**
     * @param AbstractAppEvent $event
     * Registers the trick into the database
     */
    public function sendToDatabase(AbstractAppEvent $event)
    {
        $entity = $event->getEntity();
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function deleteFromDatabase(AbstractAppEvent $event)
    {
        $entity = $event->getEntity();
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function persist(AbstractAppEvent $event)
    {
        $entity = $event->getEntity();
        $this->em->persist($entity);
    }

    public function flush()
    {
        $this->em->flush();
    }

    /**
     * @required
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }
}