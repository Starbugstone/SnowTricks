<?php

namespace App\Tests;

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

        //make sure we have at least one trick showing
        $this->assertGreaterThan(
            0,
            $crawler
                ->filter('div.card')
                ->count()
        );


    }
}