<?php

namespace App\Search;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\Video;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use function count;

class TrickSearch
{

    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var TagRepository
     */
    private $tagRepository;
    /**
     * @var ImageRepository
     */
    private $imageRepository;
    /**
     * @var VideoRepository
     */
    private $videoRepository;

    public function __construct(
        TrickRepository $trickRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        ImageRepository $imageRepository,
        VideoRepository $videoRepository
    ) {
        $this->trickRepository = $trickRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
    }

    public function searchTricks(string $query)
    {

        $query = $this->sanitizeSearchQuery($query);
        $searchTerms = $this->extractSearchTerms($query);

        if (count($searchTerms) === 0) {
            return $this->trickRepository->findAll();
        }

        $trickList = [];
        $trickList = array_merge($trickList, $this->trickRepository->findBySearchQuery($searchTerms));


        $categorySearch = $this->categoryRepository->findBySearchQuery($searchTerms);
        /** @var Category $category */
        foreach ($categorySearch as $category) {
            $trickList = array_merge($trickList, $category->getTricks()->toArray());
        }

        $tagSearch = $this->tagRepository->findBySearchQuery($searchTerms);
        /** @var Tag $tag */
        foreach ($tagSearch as $tag) {
            $trickList = array_merge($trickList, $tag->getTricks()->toArray());
        }

        $imageSearch = $this->imageRepository->findBySearchQuery($searchTerms);
        /** @var Image $image */
        foreach ($imageSearch as $image) {
            if($image->getTrick() !== null){
                $trickList[] = $image->getTrick();
            }

        }

        $videoSearch = $this->videoRepository->findBySearchQuery($searchTerms);
        /** @var Video $video */
        foreach ($videoSearch as $video) {
            if($video->getTrick() !== null){
                $trickList[] = $video->getTrick();
            }

        }

        return array_unique($trickList);

    }

    /**
     * @param string $query
     * @return string
     * Removes all non alphanum characters except whitespace
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return trim(preg_replace('/[[:space:]]+/', ' ', $query));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', $searchQuery));
        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }
}