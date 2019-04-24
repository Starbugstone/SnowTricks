<?php

namespace App\FlashMessage;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

trait AddFlashTrait{

    private $flashBag;

    /**
     * Adding te flash message to the session. This enables to have the same syntax as in the controllers
     * @param string $type
     * @param string $message
     */
    public function addFlashMessage(string $type, string $message)
    {
        $this->flashBag->add($type, $message);
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