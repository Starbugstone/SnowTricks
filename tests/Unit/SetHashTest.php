<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Security\UserSetHash;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SetHashTest extends WebTestCase
{



    public function testSetHash()
    {
        $userSetHash = new UserSetHash();

        $user = new User();
        $now = new \DateTime();

        $hash = $userSetHash->set($user);
        //testing if the hash is set and equals the returned hash
        $this->assertEquals($hash, $user->getVerifiedHash(), 'the returned hash is incorrect');

        //the set time should be now or a bit more
        $this->assertGreaterThanOrEqual($now,$user->getVerifiedDateTime(), 'the set datetime is incorrect');

    }
}