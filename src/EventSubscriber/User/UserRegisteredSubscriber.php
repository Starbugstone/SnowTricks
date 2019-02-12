<?php

namespace App\EventSubscriber\User;

use App\Event\User\UserRegisteredEvent;
use App\Services\FlashMessageCategory;
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
    private $adminEmail;

    public function __construct(
        $adminEmail,
        \Swift_Mailer $mailer,
        UserPasswordEncoderInterface $passwordEncoder,
        UserSetHash $setHash,
        EngineInterface $templating
    ) {
        $this->mailer = $mailer;
        $this->passwordEncoder = $passwordEncoder;
        $this->setHash = $setHash;
        $this->templating = $templating;
        $this->adminEmail = $adminEmail;
    }

    public function setPassword(UserRegisteredEvent $event)
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

    public function registerHash(UserRegisteredEvent $event)
    {
        $user = $event->getEntity();
        $this->setHash->set($user);
        $this->persist($event);
    }


    public function sendValidationMail(UserRegisteredEvent $event)
    {
        $user = $event->getEntity();
        $message = (new \Swift_Message('Email validation'))
            ->setFrom($this->adminEmail)
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
            ]
        ];
    }
}