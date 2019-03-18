<?php

namespace App\Serializer;

use App\Entity\Trick;
use App\Repository\TagRepository;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TagSerializer
{

    /**
     * @var TagRepository $tagRepository
     */
    private $tagRepository;

    public function trickTagsJson(Trick $trick)
    {
        return $this->serializeTag($trick->getTags());
    }

    public function allTagsJson()
    {
        return $this->serializeTag($this->tagRepository->findAll());
    }

    /**
     * @required
     * @param TagRepository $tagRepository
     */
    public function setTagRepository(TagRepository $tagRepository): void
    {
        $this->tagRepository = $tagRepository;
    }

    private function serializeTag($tags){
        $serializer = new Serializer([new ObjectNormalizer()]);
        return json_encode($serializer->normalize($tags, null, ['attributes' => ['name']]));
    }

}