<?php

namespace App\Mail;

use Symfony\Component\Templating\EngineInterface;

class SendMail
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function send(string $subject, string $template, $mailData, string $to, String $from = null):bool
    {
        if ($from === null){
            $from = getenv('ADMIN_EMAIL');
        }

        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->templating->render(
                    $template,
                    ['mailData' => $mailData]
                ),
                'text/html'
            );
        return $this->mailer->send($message)>0;

    }

}