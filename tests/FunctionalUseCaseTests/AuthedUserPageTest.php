<?php

namespace App\Tests\FunctionalUseCaseTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthedUserPageTest extends WebTestCase
{

    private $dummyTitle = "PHP Unit test";
    private $dummyText = "Some test text";
    private $dummyTitleEdit = "PHP Unit Edited test";
    private $dummyTextEdit = "Edited test text";

    private $createdTestTrickId = 0;

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'user@localhost.com',
            'PHP_AUTH_PW' => 'user',
        ]);
    }

    public function testCRUD()
    {

        //----------------
        // CREATE
        //----------------

        //going to the create page
        $crawler = $this->client->request('GET', '/trick/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //Filling out the form
        $form = $crawler->selectButton('trick_save')->form();

        $form['trick[name]'] = $this->dummyTitle;
        $form['trick[text]'] = $this->dummyText;

        //Submitting the form and following redirect
        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect()); //we should redirect the the show page
        $crawler = $this->client->followRedirect();


        //----------------
        // READ
        //----------------

        $this->readShowPage($crawler, $this->dummyTitle);


        //----------------
        // UPDATE
        //----------------

        //Go to the edit page
        $crawler = $this->client->request('GET', '/trick/edit/' . $this->createdTestTrickId);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        //making sure we are on the edit page thanks to the ID on each container
        $this->assertGreaterThan(0, $crawler->filter('div#trickEditContainer')->count());

        //Edit the trick via the form
        $form = $crawler->selectButton('trick[save]')->form();
        $form['trick[name]'] = $this->dummyTitleEdit;
        $form['trick[text]'] = $this->dummyTextEdit;

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect()); //we should redirect the the show page
        $crawler = $this->client->followRedirect();

        $this->readShowPage($crawler, $this->dummyTitleEdit);


        //----------------
        // DELETE
        //----------------

        $form = $crawler->selectButton('delete_'.$this->createdTestTrickId)->form();
        $this->client->submit($form);


        $this->assertTrue($this->client->getResponse()->isRedirect()); //we should redirect the the home page

        //test if the trick is deleted, we should get a 404
        $this->client->request('GET', '/trick/' . $this->createdTestTrickId . '-falseslug');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

    }

    private function readShowPage($crawler, $dummyTitle)
    {
        //making sure we are on the show page thanks to the ID on each container
        $this->assertGreaterThan(0, $crawler->filter('div#trickShowContainer')->count());

        //Checking we have the same title
        $title = $crawler->filter('div#trickShowContainer h1.trick-name')->first()->text();
        $this->assertEquals($dummyTitle, $title); //checking if the title is realy what we sent

        //Grabbing the ID
        $this->createdTestTrickId = $crawler->filter('div#trickShowContainer')->first()->attr('data-id');
    }

}