<?php

namespace App\EventSubscriber\Trick;

use App\Event\Trick\TrickCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

abstract class TrickSubscriber
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    public function __construct(EntityManagerInterface $em, FlashBagInterface $flashBag)
    {
        $this->em = $em;
        $this->flashBag = $flashBag;
    }

    /**
     * @return array The event names to listen to
     */
    abstract public static function getSubscribedEvents();

    /**
     * @param TrickCreatedEvent $event
     * Registers the trick into the database
     */
    public function sendToDataBase(TrickCreatedEvent $event){
        $trick = $event->getTrick();
        $this->em->persist($trick);
        $this->em->flush();
    }

    /*
     * Adding te flash message to the session. This enables to have the same syntax as in the controllers
     */
    public function addFlash(string $type, string $message){
        $this->flashBag->add($type, $message);
    }
}