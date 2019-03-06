<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserChangepasswordEvent;
use App\Event\User\UserEvent;
use App\Event\User\UserForgotpasswordEvent;
use App\Event\User\UserRegisteredEvent;
use App\Event\User\UserResetpasswordEvent;
use App\Event\User\UserUpdateAccountEvent;
use App\FlashMessage\FlashMessageCategory;
use App\Mail\SendMail;
use App\Security\UserSetHash;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAccountSubscriber extends UserSubscriber implements EventSubscriberInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserSetHash
     */
    private $setHash;
    /**
     * @var SendMail
     */
    private $mail;


    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserSetHash $setHash,
        SendMail $mail
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->setHash = $setHash;
        $this->mail = $mail;
    }

    public function setPassword(UserEvent $event)
    {
        $user = $event->getEntity();
        $password = $event->getPlainPassword();
        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $this->persist($event);
    }

    public function registerHash(UserEvent $event)
    {
        $user = $event->getEntity();
        $this->setHash->set($user);
        $this->persist($event);
    }

    public function updateUser(UserEvent $event)
    {
        $this->persist($event);
    }

    public function sendForgotpasswordMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $this->mail->send(
            'Forgot Password',
            'emails/forgotpassword.html.twig',
            $user,
            $user->getEmail()
        );
    }

    public function sendResetpasswordMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $this->mail->send(
            'Reset Password',
            'emails/resetpassword.html.twig',
            $user,
            $user->getEmail()
        );
    }


    public function sendValidationMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $mailSent = $this->mail->send(
            'Email Validation',
            'emails/hash.html.twig',
            $user,
            $user->getEmail()
        );

        if ($mailSent) {
            $this->addFlash(FlashMessageCategory::SUCCESS, "A validation mail has been sent to " . $user->getEmail());
            return;
        }

        $this->addFlash(FlashMessageCategory::ERROR, "Error sending email");
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => [
                ['setPassword', 50],
                ['registerHash', 40],
                ['flush', 20],
                ['sendValidationMail', 10],
            ],
            UserForgotpasswordEvent::NAME => [
                ['registerHash', 40],
                ['flush', 20],
                ['sendForgotpasswordMail', 10],
            ],
            UserResetpasswordEvent::NAME => [
                ['setPassword', 50],
                ['flush', 20],
                ['sendResetpasswordMail', 10],
            ],
            UserChangepasswordEvent::NAME => [
                ['setPassword', 50],
                ['flush', 20],
            ],
            UserUpdateAccountEvent::NAME => [
                ['updateUser', 50],
                ['flush', 20],
            ],
        ];
    }
}