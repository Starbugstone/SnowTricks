<?php

namespace App\EventSubscriber;

use App\Event\AppEvent;
use App\FlashMessage\AddFlashTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class AppSubscriber
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
     * @param AppEvent $event
     * Registers the trick into the database
     */
    public function sendToDatabase(AppEvent $event)
    {
        $entity = $event->getEntity();
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function deleteFromDatabase(AppEvent $event)
    {
        $entity = $event->getEntity();
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function persist(AppEvent $event)
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