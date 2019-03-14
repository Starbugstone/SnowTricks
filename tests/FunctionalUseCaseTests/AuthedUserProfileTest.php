<?php

namespace App\Tests\FunctionalUseCaseTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthedUserProfileTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'user@localhost.com',
            'PHP_AUTH_PW' => 'user',
        ]);
    }

    public function testProfile()
    {
        $crawler = $this->client->request('GET', '/profile');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('user_profile_form_updateProfile')->form();
        $form['user_profile_form[UserName]'] = 'UserTest';

        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('user_profile_form_updateProfile')->form();
        $newUserName = $form->get('user_profile_form[UserName]')->getValue();

        $this->assertEquals('UserTest',$newUserName );

    }
}