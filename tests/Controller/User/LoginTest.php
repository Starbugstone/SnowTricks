<?php

namespace App\Tests\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{

    public function testLoginPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        //Making sure that we got a page
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //Checking if we have all the elements
        $this->assertEquals(1, $crawler->filter('input#inputEmail')->count());
        $this->assertEquals(1, $crawler->filter('input#inputPassword')->count());
        $this->assertEquals(1, $crawler->filter('input#remember_me')->count());
        $this->assertEquals(1, $crawler->filter('p#forgotPasswordLarge')->count());
        $this->assertEquals(1, $crawler->filter('p#forgotPasswordSmall')->count());
    }

    public function testLoginGoodEmail()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'admin@localhost.com';
        $form['password'] = 'admin';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect()); //we should redirect the front page
        $this->assertEquals('/profile', $client->getResponse()->getTargetUrl(), 'We are not redirecting to Home page');

        $crawler = $client->followRedirect();

        //check if we have a security element in session, this smells a bit like a hack, might need to do some more research
        $this->assertGreaterThan(0, strlen($client->getRequest()->getSession()->get('_security_main')));

    }

    public function testLoginGoodUser()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'admin';
        $form['password'] = 'admin';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect()); //we should redirect the front page
        $this->assertEquals('/profile', $client->getResponse()->getTargetUrl(), 'We are not redirecting to Home page');

        $crawler = $client->followRedirect();

        //check if we have a security element in session, this smells a bit like a hack, might need to do some more research
        $this->assertGreaterThan(0, strlen($client->getRequest()->getSession()->get('_security_main')));

    }

    public function testLoginBad()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();

        $form['email'] = 'Unknownuser';
        $form['password'] = 'badPass';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect()); //we should redirect the the same page after post
        $this->assertEquals('/login', $client->getResponse()->getTargetUrl(), 'We are not redirecting to Login');

        $crawler = $client->followRedirect();

        //checking if we have a bad login message
        $this->assertGreaterThan(0, $crawler->filter('div.alert-danger')->count(),'No alert shown');

        $form = $crawler->selectButton('Sign in')->form();

        //checking if we saved the login
        $this->assertEquals('Unknownuser', $form['email']->getValue(), 'The username was not saved');

    }

}