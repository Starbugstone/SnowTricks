<?php

namespace App\Search;

use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use App\Repository\TrickRepository;

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

    public function __construct(TrickRepository $trickRepository, CategoryRepository $categoryRepository, TagRepository $tagRepository)
    {
        $this->trickRepository = $trickRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    public function searchTricks(string $query)
    {

        $query = $this->sanitizeSearchQuery($query);
        $searchTerms = $this->extractSearchTerms($query);

        if (\count($searchTerms) === 0) {
            return $this->trickRepository->findAll();
        }

        $trickList = [];
        $trickList = array_merge($trickList, $this->trickRepository->findBySearchQuery($searchTerms));


        $categorySearch = $this->categoryRepository->findBySearchQuery($searchTerms);
        foreach ($categorySearch as $category) {
            $trickList = array_merge($trickList, $category->getTricks()->toArray());
        }

        $tagSearch = $this->tagRepository->findBySearchQuery($searchTerms);
        foreach ($tagSearch as $tag){
            $trickList = array_merge($trickList, $tag->getTricks()->toArray());
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