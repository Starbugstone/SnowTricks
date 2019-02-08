<?php

namespace App\EventSubscriber;

use App\Event\AppEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class AppSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * @return array The event names to listen to
     */
    abstract public static function getSubscribedEvents();

    /**
     * @param AppEvent $event
     * Registers the trick into the database
     */
    public function sendToDataBase(AppEvent $event)
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

    /**
     * Adding te flash message to the session. This enables to have the same syntax as in the controllers
     * @param string $type
     * @param string $message
     */
    public function addFlash(string $type, string $message)
    {
        $this->flashBag->add($type, $message);
    }

    /**
     * @required
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @required
     * @param FlashBagInterface $flashBag
     */
    public function setFlashBag(FlashBagInterface $flashBag): void
    {
        $this->flashBag = $flashBag;
    }
}