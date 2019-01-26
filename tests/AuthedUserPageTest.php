<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthedUserPageTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'user@localhost.com',
            'PHP_AUTH_PW' => 'user',
        ]);

    }

    public function testCreatePage()
    {
        //going to a page that needs user auth
        $this->client->request('GET', '/trick/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditPage()
    {
        //get the edit link
        $link = $this->goToSearchPageLink('create');
        //click the link to go to the edit page and check if available
        $this->client->click($link->link());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    public function testDeletePage()
    {
        $link = $this->goToSearchPageLink('delete');

        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function goToSearchPageLink(string $linkText)
    {
        //going to the search page
        $crawler = $this->client->request('GET', '/search');
        //making sure the search page exists
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //get the 1st edit link
        $link = $crawler
            ->filter('a:contains(' . $linkText . ')')// find all links with the text
            ->eq(0) // select the 1st link in the list
        ;

        //make sure the link is available, only visible if authed
        $this->assertGreaterThan(
            0,
            $link->count()
        );

        return $link;


    }


}