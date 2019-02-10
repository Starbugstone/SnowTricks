<?php

namespace App\Services;


use App\Entity\User;

class UserSetHash
{

    /**
     * @param User $user
     * @return string
     * @throws \Exception
     * Sets a new hash to the verifiedDateTimeField of the passed user
     */
    public function set(User $user): string
    {
        $hash = bin2hex(random_bytes(16));
        $user->setVerifiedHash($hash);

        $user->setVerifiedDateTime(new \DateTime());

        return $hash;
    }
}