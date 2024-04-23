<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\TechnologyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Technology
{
    const PATH_TECHNOLOGY = "/technology/images/";

    use EntityTimestampTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['technology:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['technology:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['technology:read'])]
    public ?string $image = null;

    #[Vich\UploadableField(mapping: 'technology_logo', fileNameProperty: 'image')]
    private ?File $imageFile = null;


    #[Groups(['technology:read'])]
    public ?string $path = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $filePathWeb = sprintf('%s/../../public/%s%s', __DIR__, Technology::PATH_TECHNOLOGY, $this->image);

            if (file_exists($filePathWeb)) {
                unlink($filePathWeb);
            }
        }
    }
}
