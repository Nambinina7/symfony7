<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\BannerItemsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: BannerItemsRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class BannerItems
{
    use EntityTimestampTrait;
    public const PATH_MOBILE = "/banners/images/mobile/";
    public const PATH_WEB = "/banners/images/web/";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['banner:read'])]
    public ?string $image = null;

    #[Vich\UploadableField(mapping: 'banner_items', fileNameProperty: 'image')]
    private ?File $imageFile = null;


    #[Groups(['banner:read'])]
    public ?string $path = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['banner:read'])]
    private ?int $orderNumber = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['banner:read'])]
    private ?float $duration = null;

    #[ORM\Column(length: 255)]
    #[Groups(['banner:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['banner:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'bannerItems')]
    private ?Banner $banner = null;

    public function __construct()
    {
        $this->duration = 10;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?int $orderNumber): static
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): static
    {
        $this->duration = $duration;

        return $this;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[ORM\PreRemove]
    public function removeImageFile(): void
    {
        if ($this->image) {
            $filePathWeb = sprintf('%s/../../public/%s%s', __DIR__, BannerItems::PATH_WEB, $this->image);

            if (file_exists($filePathWeb)) {
                unlink($filePathWeb);
            }
        }
    }

    public function getBanner(): ?Banner
    {
        return $this->banner;
    }

    public function setBanner(?Banner $banner): static
    {
        $this->banner = $banner;

        return $this;
    }
}
