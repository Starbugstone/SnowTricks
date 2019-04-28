<?php
namespace App\Uploader\Annotation;

use Doctrine\Common\Annotations\Reader;

class UploadAnnotationReader {

    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Liste les champs uploadable d'une entité (sous forme de tableau associatif)
     */
    public function getUploadableFields($entity): array {
        $reflection = new \ReflectionClass(get_class($entity));
        $properties = [];
        foreach($reflection->getProperties() as $property) {
            $annotation = $this->reader->getPropertyAnnotation($property, UploadableField::class);
            if ($annotation !== null) {
                $properties[$property->getName()] = $annotation;

            }
        }
        return $properties;
    }

}