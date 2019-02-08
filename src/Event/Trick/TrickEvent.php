<?php

namespace App\Event\Trick;

use App\Entity\Trick;
use Symfony\Component\EventDispatcher\Event;

abstract class TrickEvent extends Event
{
    const NAME='defineMe';

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