<?php

namespace App\Services\Registration;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationSetHash
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return string
     * @throws \Exception
     * Sets a new hash to the verifiedDateTimeField of the passed user
     */
    public function setHash(User $user): string
    {
        $hash = bin2hex(random_bytes(16));
        $user->setVerifiedHash($hash);

        $user->setVerifiedDateTime(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();
        return $hash;
    }
}