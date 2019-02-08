<?php

namespace App\Event;

use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;

class TrickCreatedEvent extends Event
{

    const NAME = 'trick.created';

    /**
     * @var Trick
     */
    private $trick;

    public function __construct(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }

}