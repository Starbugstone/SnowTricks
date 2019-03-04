<?php

namespace App\Tests\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    public function testRegisterPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/user/register');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('input#registration_form_userName')->count());
        $this->assertEquals(1, $crawler->filter('input#registration_form_email')->count());
        $this->assertEquals(1, $crawler->filter('input#registration_form_plainPassword')->count());
    }

    public function testGoodRegistration()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/user/register');


        $form = $crawler->selectButton('Register')->form();

        $form['registration_form[userName]'] = 'azerty';
        $form['registration_form[email]'] = 'azerty@localhost.dev';
        $form['registration_form[plainPassword]'] = 'azerty123';

        //enable profiler to test for mails
        $client->enableProfiler();

        $client->submit($form);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertEquals(1, $mailCollector->getMessageCount(), 'registration mail was not sent');

        //Looking to test for events
        //var_dump($client->getProfile()->getCollector('events'));

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals('/', $client->getResponse()->getTargetUrl(), 'We are not redirecting to Home page');

        //prehaps test for flash message

    }
}