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
     * List uploadable fields from an entity as a table
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