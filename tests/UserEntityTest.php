<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    private $user;

    //creating our test user
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->user = new User();

        $this->user
            ->setEmail('test@localhost.com')
            ->setPassword('azerty')
            ->setUserName('test')
        ;
    }

    public function testIsHashValid()
    {
        $hash = '123456abc';
        $this->user->setVerifiedHash($hash);
        $this->assertTrue($this->user->isHashValid($hash));
        $this->assertFalse($this->user->isHashValid($hash.'a'));

    }

    public function testIsVerifiedDateTime()
    {
        $now = new \DateTime();

        $now->sub(new \DateInterval('PT20M')); //setting 20 minutes into the past should be good
        $this->user->setVerifiedDateTime($now);
        $this->assertTrue($this->user->isVerifiedDateTimeValid());

        $now->sub(new \DateInterval('P70D'));//setting to 70 days old should be bad
        $this->user->setVerifiedDateTime($now);
        $this->assertFalse($this->user->isVerifiedDateTimeValid());

    }
}