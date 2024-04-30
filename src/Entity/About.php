<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\AboutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AboutRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class About
{
    use EntityTimestampTrait;

    public const PATH_ABOUT = "/about/images/";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['about:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['about:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['about:read'])]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'about_upload', fileNameProperty: 'image')]
    #[SerializedName('src')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    #[Groups(['about:read'])]
    public ?string $image = null;

    #[Groups(['about:read'])]
    public ?string $path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }


    public function setImageFile(File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    #[ORM\PreRemove]
    public function removeImageFile(): void
    {
        if ($this->image) {
            $filePathWeb = sprintf('%s/../../public/%s%s', __DIR__, About::PATH_ABOUT, $this->image);

            if (file_exists($filePathWeb)) {
                unlink($filePathWeb);
            }
        }
    }
}
