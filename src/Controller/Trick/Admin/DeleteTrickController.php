<?php

namespace App\Controller\Trick\Admin;

use App\Entity\Trick;
use App\Event\Trick\TrickDeletedEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class TrickEditController
 * @package App\Controller\Edit
 *
 * Require the user to be connected for everything here
 * @IsGranted("ROLE_USER")
 */
class DeleteTrickController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/trick/{id}/delete", name="trick.delete")
     */
    public function delete(Trick $trick)
    {
        $event = new TrickDeletedEvent($trick);
        $this->dispatcher->dispatch(TrickDeletedEvent::NAME, $event);

        return $this->redirectToRoute('home');
    }


}