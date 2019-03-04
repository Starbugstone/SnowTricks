<?php

namespace App\History;

use Doctrine\ORM\EntityManagerInterface;

class TrickHistory
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $this->em->getRepository('Gedmo\Loggable\Entity\LogEntry');
    }

    public function getHistory($id)
    {
        $trick = $this->em->find('App\Entity\Trick', $id);
        $logs = $this->repo->getLogEntries($trick);

        return $logs;
    }

    public function revertToHistory($id, $historyId){
        $trick = $this->em->find('App\Entity\Trick', $id);
        $this->repo->revert($trick, $historyId);
        $this->em->persist($trick);
        $this->em->flush();
    }




}