<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitorPageTest extends WebTestCase
{

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testReadTricks()
    {
        $crawler = $this->client->request('GET', '/');

        //make sure the front page replies
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //make sure we have at least one trick showing
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('div.card')
                ->count()
        );
    }

    public function testPrivateSectionRedirect()
    {
        //going to the create page
        $this->client->request('GET', '/trick/new');

        $this->assertTrue($this->client->getResponse()->isRedirect()); //not authed, should be a redirect
        $crawler = $this->client->followRedirect();

        //making sure we are on the login page
        $this->assertGreaterThan(0, $crawler->filter('div#trickLoginContainer')->count());


        //TODO check the edit page
    }
}