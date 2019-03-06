<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@localhost.com')
            ->setUserName('admin')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'
            ))
            ->setRoles(['ROLE_ADMIN'])
            ->setVerified(true)
            ->setVerifiedHash(bin2hex(random_bytes(16)));

        // $product = new Product();
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user@localhost.com')
            ->setUserName('user')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user'
            ))
            ->setVerified(true)
            ->setVerifiedHash(bin2hex(random_bytes(16)));

        // $product = new Product();
        $manager->persist($user);

        $user = new User();
        $user->setEmail('user2@localhost.com')
            ->setUserName('user2')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user'
            ))
            ->setVerified(false)
            ->setVerifiedHash(bin2hex(random_bytes(16)));

        // $product = new Product();
        $manager->persist($user);

        $manager->flush();
    }
}
