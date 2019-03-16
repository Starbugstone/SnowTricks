<?php

namespace App\tests\Form\DataTransformer;

use App\Entity\Tag;
use App\Form\DataTransformer\TagsToJsonTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class TagsToJsonTransformerTest extends TestCase
{

    public function testTagsToArray()
    {
        $transformer = $this->getMockedTransformer();

        $tags = $transformer->reverseTransform('["test","test2"]');
        $this->assertCount(2, $tags);
        $this->assertEquals('test2', $tags[1]->getName());
    }

    public function testAlreadyUsedTagToArray()
    {
        $tag = new Tag();
        $tag->setName('cat');

        $transformer = $this->getMockedTransformer([$tag]);

        $tags = $transformer->reverseTransform('["cat","dog"]');
        $this->assertCount(2, $tags);
        $this->assertSame($tag, $tags[0]);
    }

    public function testArraytoJson(){
        $tag1 = new Tag();
        $tag2 = new Tag();

        $tag1->setName('Cat');
        $tag2->setName('Dog');

        $tagArray[] = $tag1;
        $tagArray[] = $tag2;

        $transformer = $this->getMockedTransformer();

        $jsonTags = $transformer->transform($tagArray);
        //all tags are forced to lower case because of DB interactions
        $this->assertEquals('["cat","dog"]', $jsonTags);

    }

    private function getMockedTransformer($result = [])
    {

        $tagRepository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagRepository->expects($this->any())
            ->method('findBy')
            ->will($this->returnValue($result));

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($tagRepository));

        return new TagsToJsonTransformer($entityManager);
    }


}