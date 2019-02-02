<?php

namespace App\Services\Registration;

use App\Entity\User;
use Symfony\Component\Templating\EngineInterface;


class RegistrationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var EngineInterface
     */
    private $templating;
    private $adminEmail;

    public function __construct($adminEmail, \Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->adminEmail = $adminEmail;
    }

    /**
     * Send the hash link to the user
     * @param User $user
     * @return int The number of successful recipients. Can be 0 which indicates failure
     */
    public function sendHash(User $user)
    {
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
        return $this->mailer->send($message);
    }
}