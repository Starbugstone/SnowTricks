<?php

namespace App\Security;


use App\Entity\User;
use App\Exception\RedirectException;
use App\FlashMessage\AddFlashTrait;
use App\FlashMessage\FlashMessageCategory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserValidator
{
    use AddFlashTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var User
     */
    private $user;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $token
     * @return bool
     * Check if the passed token is valid to register the mail
     * @throws NonUniqueResultException
     */
    public function isUserTokenValid(string $token):bool
    {
        $this->retrieveUserFromToken($token);

        if($this->isUserVerified()){
            $this->addFlash(FlashMessageCategory::ERROR, 'Mail already verified');
            throw new RedirectException($this->urlGenerator->generate('app_login'));
        }

        return $this->isUserVerifiedDateTime();

    }

    /**
     * @param string $token
     * @return bool
     * Check if the reset password token is valid
     * @throws NonUniqueResultException
     */
    public function doesResetpasswordTokenValidateEmail(string $token):bool
    {
        $this->retrieveUserFromToken($token);
        if(!$this->isUserVerifiedDateTime()){
            $this->addFlash(FlashMessageCategory::ERROR, 'Token is too old, please use this form to resend a link');
            throw new RedirectException($this->urlGenerator->generate('app_forgotpassword'));
        }
        return !$this->isUserVerified();
    }

    /**
     * @param string $token
     * @return User|null
     * @throws NonUniqueResultException
     * gets the user from the token and redirects on error
     */
    public function retrieveUserFromToken(string $token): ?User
    {
        $user = $this->em->getRepository(User::class)->findUserByhash($token);
        if (!$user) {
            //no user found
            $this->addFlash(FlashMessageCategory::ERROR, 'Invalid Token, please use this form to resend a link');
            throw new RedirectException($this->urlGenerator->generate('app_forgotpassword'));
        }

        $this->user = $user;
        return $this->user;
    }

    /**
     * @return bool
     * @throws Exception
     * Checks if the token is still valid
     */
    private function isUserVerifiedDateTime():bool
    {
        if($this->user){
            return $this->user->isVerifiedDateTimeValid();
        }
        return false;

    }

    /**
     * @return bool
     * checks if the user has already validated his account
     */
    private function isUserVerified():bool
    {
        if($this->user){
            return $this->user->getVerified();
        }
        return false;
    }

}