<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\HolydayRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HolydayRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Holyday
{
    use EntityTimestampTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['holyday:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['holyday:read', 'holyday:write'])]
    private ?\DateTimeInterface $requestDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['holyday:read', 'holyday:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['holyday:read', 'holyday:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['holyday:read', 'holyday:write'])]
    private ?string $leaveReasons = null;

    #[ORM\Column(length: 255)]
    #[Groups(['holyday:read', 'holyday:write'])]
    private ?string $status = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'holydays')]
    #[Groups(['holyday:read'])]
    private Collection $userHolydays;

    #[ORM\Column(nullable: true)]
    private ?int $total = null;

    public function __construct()
    {
        $this->userHolydays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->requestDate;
    }

    public function setRequestDate(\DateTimeInterface $requestDate): static
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLeaveReasons(): ?string
    {
        return $this->leaveReasons;
    }

    public function setLeaveReasons(string $leaveReasons): static
    {
        $this->leaveReasons = $leaveReasons;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserHolydays(): Collection
    {
        return $this->userHolydays;
    }

    public function addUserHolyday(User $userHolyday): static
    {
        if (!$this->userHolydays->contains($userHolyday)) {
            $this->userHolydays->add($userHolyday);
        }

        return $this;
    }

    public function removeUserHolyday(User $userHolyday): static
    {
        $this->userHolydays->removeElement($userHolyday);

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): static
    {
        $this->total = $total;

        return $this;
    }
}
