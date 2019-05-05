<?php

namespace App\Test\Search;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Trick;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use App\Search\TrickSearch;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class SearchTest extends TestCase
{

    public function testSearchTrickName()
    {
        $trick = new Trick();
        $trick->setName('test');
        $search = $this->getMockedSearch([$trick]);

        $foundTricks = $search->searchTricks('dull');
        $this->assertCount(1, $foundTricks);
        $this->assertSame($trick, $foundTricks[0]);

    }

    public function testSearchCategoryName()
    {
        $trick = new Trick();
        $trick->setName('test');
        $category = new Category();
        $category->setName('testCategory');
        $category->addTrick($trick);
        $search = $this->getMockedSearch([],[1=>$category]);

        $foundTricks = $search->searchTricks('dull');
        $this->assertCount(1, $foundTricks);
        $this->assertSame($trick, $foundTricks[0]);

    }
    public function testSearchTagName()
    {
        $trick = new Trick();
        $trick->setName('test');
        $tag = new Tag();
        $tag->setName('testTag');
        $tag->addTrick($trick);
        $search = $this->getMockedSearch([],[],[1=>$tag]);

        $foundTricks = $search->searchTricks('dull');
        $this->assertCount(1, $foundTricks);
        $this->assertSame($trick, $foundTricks[0]);

    }

    public function testMultipleSearchResults(){
        $trick = new Trick();
        $trick->setName('test');
        $trick2 = new Trick();
        $trick2->setName('test2');
        $trick3 = new Trick();
        $trick3->setName('test3');

        $category = new Category();
        $category->setName('testCategory');
        $category->addTrick($trick2);

        $tag = new Tag();
        $tag->setName('testTag');
        $tag->addTrick($trick3);
        $search = $this->getMockedSearch([$trick],[1=>$category],[1=>$tag]);

        $foundTricks = $search->searchTricks('dull');
        $this->assertCount(3, $foundTricks);
        $this->assertSame($trick, $foundTricks[0]);
        $this->assertSame($trick2, $foundTricks[1]);
        $this->assertSame($trick3, $foundTricks[2]);
    }

    private function getMockedSearch($trickResults = [], $categoryResults = [], $tagResults = [], $imageResults = [], $videoResults = [])
    {
        $trickRepository = $this->getMockBuilder(TrickRepository::class)
        ->disableOriginalConstructor()
        ->getMock();

        $trickRepository->expects($this->any())
            ->method('findBySearchQuery')
            ->will($this->returnValue($trickResults));

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $categoryRepository->expects($this->any())
            ->method('findBySearchQuery')
            ->will($this->returnValue(new ArrayCollection($categoryResults)));

        $tagRepository = $this->getMockBuilder(TagRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tagRepository->expects($this->any())
            ->method('findBySearchQuery')
            ->will($this->returnValue(new ArrayCollection($tagResults)));

        $imageRepository = $this->getMockBuilder(ImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $imageRepository->expects($this->any())
            ->method('findBySearchQuery')
            ->will($this->returnValue(new ArrayCollection($imageResults)));

        $videorepository = $this->getMockBuilder(VideoRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $videorepository->expects($this->any())
            ->method('findBySearchQuery')
            ->will($this->returnValue(new ArrayCollection($videoResults)));

        return new TrickSearch($trickRepository, $categoryRepository, $tagRepository, $imageRepository, $videorepository);
    }
}