<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;


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
        $faker = Factory::create();

        $user = new User();
        $user->setEmail('admin@localhost.com')
            ->setUserName('admin')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'
            ))
            ->setRoles(['ROLE_ADMIN'])
            ->setImage($faker->image('public/uploads/images',400,300, null, false) )
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
            ->setImage($faker->image('public/uploads/images',400,300, null, false) )
            ->setVerified(true)
            ->setVerifiedHash(bin2hex(random_bytes(16)));

        // $product = new Product();
        $manager->persist($user);

        $user = new User();
        $user->setEmail('usertest@localhost.com')
            ->setUserName('usertest')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user'
            ))
            ->setVerified(false)
            ->setVerifiedHash(bin2hex(random_bytes(16)));

        $manager->persist($user);



        //Adding extra users

        for($i=0; $i<10; $i++){
            $user = new User();
            $user->setEmail('user'.$i.'@localhost.com')
                ->setUserName('user'.$i)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'user'
                ))

                ->setVerified(true)
                ->setVerifiedHash(bin2hex(random_bytes(16)));

            //Not all users will have an image
            if(rand(0,2)>=1){
                $user->setImage($faker->image('public/uploads/images',400,300, null, false) );
            }
            // $product = new Product();
            $manager->persist($user);
        }

        $manager->flush();

    }
}
