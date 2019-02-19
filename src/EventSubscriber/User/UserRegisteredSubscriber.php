<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserEvent;
use App\Event\User\UserForgotpasswordEvent;
use App\Event\User\UserRegisteredEvent;
use App\Event\User\UserResetpasswordEvent;
use App\FlashMessage\FlashMessageCategory;
use App\Security\UserSetHash;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Templating\EngineInterface;

class UserRegisteredSubscriber extends UserSubscriber implements EventSubscriberInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserSetHash
     */
    private $setHash;
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(
        \Swift_Mailer $mailer,
        UserPasswordEncoderInterface $passwordEncoder,
        UserSetHash $setHash,
        EngineInterface $templating
    ) {
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
        $this->setHash = $setHash;
        $this->templating = $templating;
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

    public function sendForgotpasswordMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $message = (new \Swift_Message('Password Reset'))
            ->setFrom(getenv('ADMIN_EMAIL'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/forgotpassword.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }

    public function sendResetpasswordMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $message = (new \Swift_Message('Reset Password'))
            ->setFrom(getenv('ADMIN_EMAIL'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/resetpassword.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }


    public function sendValidationMail(UserEvent $event)
    {
        $user = $event->getEntity();
        $message = (new \Swift_Message('Email validation'))
            ->setFrom(getenv('ADMIN_EMAIL'))
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/hash.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );
        if($this->mailer->send($message)>0){
            $this->addFlash(FlashMessageCategory::SUCCESS, "A validation mail has been sent to ".$user->getEmail());
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
            UserForgotpasswordEvent::NAME=>[
                ['registerHash', 40],
                ['flush', 20],
                ['sendForgotpasswordMail', 10],
            ],
            UserResetpasswordEvent::NAME=>[
                ['setPassword', 50],
                ['flush', 20],
                ['sendResetpasswordMail', 10],
            ],
        ];
    }
}