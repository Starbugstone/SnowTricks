<?php

namespace App\tests\Serailzer;

use App\Entity\Tag;
use App\Entity\Trick;
use App\Serializer\TagSerializer;
use PHPUnit\Framework\TestCase;

class TagSerailzerTest extends TestCase{

    public function testSerializedTag(){

        $tag1 = new Tag();
        $tag1->setName('testtag1');

        $trick = new Trick();

        $trick->setName('BlaBlaBla');
        $trick->addTag($tag1);
        $tagSerializer = new TagSerializer();

        $return = $tagSerializer->trickTagsJson($trick);

        $this->assertEquals("[{\"name\":\"testtag1\"}]", $return);
    }

    public function testSerializedTags(){

        $tag1 = new Tag();
        $tag1->setName('testtag1');
        $tag2 = new Tag();
        $tag2->setName('testtag2');

        $trick = new Trick();

        $trick->setName('BlaBlaBla');
        $trick->addTag($tag1);
        $trick->addTag($tag2);
        $tagSerializer = new TagSerializer();

        $return = $tagSerializer->trickTagsJson($trick);

        $this->assertEquals("[{\"name\":\"testtag1\"},{\"name\":\"testtag2\"}]", $return);
    }
}