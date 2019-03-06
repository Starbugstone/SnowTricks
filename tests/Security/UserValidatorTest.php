<?php

namespace App\Tests\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserValidatorTest extends WebTestCase
{

    private $user;
    private $entityManager;
    private $urlGenerator;

    public function setUp()
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->user = new User();
        $this->user->setVerifiedHash('abcd1234');

    }

    public function testRetrieveUserFromTokenOk()
    {
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findUserByHash')->willReturn($this->user);
        $this->entityManager->method('getRepository')->willReturn($userRepository);

        $userValidator = new UserValidator($this->entityManager, $this->urlGenerator);
        $this->assertEquals($this->user, $userValidator->retrieveUserFromToken('abcd1234'));

    }

    public function tearDown()
    {
        $this->user = null;
        $this->entityManager = null;
        $this->urlGenerator = null;
    }
}