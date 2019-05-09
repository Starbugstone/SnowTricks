<?php

namespace App\Entity;

use App\Uploader\Annotation\UploadableField;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image extends AbstractAppEntity
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage = "Image title must be at least {{ limit }} characters",
     *     maxMessage = "Image title can not exceed {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Assert\Image()
     * @UploadableField(filename="image", path="uploads/trick_images")
     * @var File
     *
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $trick;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $primaryImage = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getWebImage(): ?string
    {
        return '/uploads/trick_images/'.$this->image;
    }


    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return Image
     * @throws Exception
     */
    public function setImageFile(File $imageFile): Image
    {
        $this->imageFile = $imageFile;
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getPrimaryImage(): ?bool
    {
        return $this->primaryImage;
    }

    public function setPrimaryImage(bool $primaryImage): self
    {
        $this->primaryImage = $primaryImage;

        return $this;
    }
}
