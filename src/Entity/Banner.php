<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\BannerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BannerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Banner
{
    use EntityTimestampTrait;
    const ROUTE_MOBILE = "get_banners_mobile";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?bool $autoPlay = null;

    #[ORM\Column(length: 255)]
    #[Groups(['banner:read'])]
    private ?string $animation = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?bool $indicators = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?int $timeout = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?bool $navButtonsAlwaysVisible = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?bool $cycleNavigation = null;

    #[ORM\Column]
    #[Groups(['banner:read'])]
    private ?int $indexBanner = null;

    #[ORM\OneToMany(targetEntity: BannerItems::class, mappedBy: 'banner')]
    #[Groups(['banner:read'])]
    private Collection $bannerItems;

    public function __construct()
    {
        $this->bannerItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAutoPlay(): ?bool
    {
        return $this->autoPlay;
    }

    public function setAutoPlay(bool $autoPlay): static
    {
        $this->autoPlay = $autoPlay;

        return $this;
    }

    public function getAnimation(): ?string
    {
        return $this->animation;
    }

    public function setAnimation(string $animation): static
    {
        $this->animation = $animation;

        return $this;
    }

    public function isIndicators(): ?bool
    {
        return $this->indicators;
    }

    public function setIndicators(bool $indicators): static
    {
        $this->indicators = $indicators;

        return $this;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): static
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function isNavButtonsAlwaysVisible(): ?bool
    {
        return $this->navButtonsAlwaysVisible;
    }

    public function setNavButtonsAlwaysVisible(bool $navButtonsAlwaysVisible): static
    {
        $this->navButtonsAlwaysVisible = $navButtonsAlwaysVisible;

        return $this;
    }

    public function isCycleNavigation(): ?bool
    {
        return $this->cycleNavigation;
    }

    public function setCycleNavigation(bool $cycleNavigation): static
    {
        $this->cycleNavigation = $cycleNavigation;

        return $this;
    }

    public function getIndexBanner(): ?int
    {
        return $this->indexBanner;
    }

    public function setIndexBanner(int $indexBanner): static
    {
        $this->indexBanner = $indexBanner;

        return $this;
    }

    /**
     * @return Collection<int, BannerItems>
     */
    public function getBannerItems(): Collection
    {
        return $this->bannerItems;
    }

    public function addBannerItem(BannerItems $bannerItem): static
    {
        if (!$this->bannerItems->contains($bannerItem)) {
            $this->bannerItems->add($bannerItem);
            $bannerItem->setBanner($this);
        }

        return $this;
    }

    public function removeBannerItem(BannerItems $bannerItem): static
    {
        if ($this->bannerItems->removeElement($bannerItem)) {
            // set the owning side to null (unless already changed)
            if ($bannerItem->getBanner() === $this) {
                $bannerItem->setBanner(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->animation;
    }
}
