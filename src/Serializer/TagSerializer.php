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
        $serializer = new Serializer([new ObjectNormalizer()]);

        return json_encode($serializer->normalize($trick->getTags(), null, ['attributes' => ['name']]));
    }

    public function allTagsJson()
    {
        $serializer = new Serializer([new ObjectNormalizer()]);

        return json_encode($serializer->normalize($this->tagRepository->findAll(), null, ['attributes' => ['name']]));
    }

    /**
     * @required
     * @param TagRepository $tagRepository
     */
    public function setTagRepository(TagRepository $tagRepository): void
    {
        $this->tagRepository = $tagRepository;
    }


}