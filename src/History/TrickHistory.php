<?php

namespace App\History;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\LogEntry;

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

    /**
     * @param $id
     * @return LogEntry[]
     * Get the modification history of a trick
     */
    public function getHistory($id)
    {
        $trick = $this->em->find('App\Entity\Trick', $id);
        $logs = $this->repo->getLogEntries($trick);

        return $logs;
    }

    /**
     * @param $id
     * @param $version
     * Revert a trick to a history checkpoint.
     */
    public function revertToHistory($id, $version){
        $trick = $this->em->find('App\Entity\Trick', $id);
        $this->repo->revert($trick, $version);
        $this->em->persist($trick);
        $this->em->flush();
    }




}