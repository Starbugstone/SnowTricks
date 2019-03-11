<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class TagsToJsonTransformer implements DataTransformerInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }

    public function transform($tagsArray)
    {
        $jsonTags = [];
        /**
         * @var $tag Tag
         */
        foreach ($tagsArray as $tag) {
            array_push($jsonTags, $tag->getName());
        }
        return json_encode($jsonTags);

    }

    public function reverseTransform($jsonTags)
    {
        $tagsArray = json_decode($jsonTags);

        $tags = $this->em->getRepository(Tag::class)->findBy([
            'name' => $tagsArray
        ]);

        $newTags = array_diff($tagsArray, $tags);

        foreach ($newTags as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $tags[] = $tag;
        }

        return $tags;

    }


}