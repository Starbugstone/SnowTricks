<?php

namespace App\Tests\Mail;

use App\Mail\SendMail;
use PHPUnit\Framework\TestCase;

class SendMailTest extends TestCase
{

    private $mailer, $templating;

    public function setUp()
    {
        $this->mailer = $this->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->setMethods(['send'])
            ->getMock();
        $this->templating = $this->getMockBuilder('Symfony\Component\Templating\EngineInterface')
            ->disableOriginalConstructor()
            ->setMethods(['render', 'exists', 'supports'])
            ->getMock();
    }

    public function testMailHasError()
    {
        $this->mailer
            ->expects($this->once())
            ->method('send')
            ->willReturn(0)
        ;

        $sendMail = new SendMail($this->mailer, $this->templating);

        $response = $sendMail->send('Test', 'nothing.html.twig', new \stdClass(), 'null@localhost.dev');
        $this->assertFalse($response);
    }

    public function testMailSent()
    {
        $this->mailer
            ->expects($this->once())
            ->method('send')
            ->willReturn(1)
        ;

        $sendMail = new SendMail($this->mailer, $this->templating);

        $response = $sendMail->send('Test', 'rien.html.twig', new \stdClass(), 'null@localhost.dev');
        $this->assertTrue($response);
    }

}