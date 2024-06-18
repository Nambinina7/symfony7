<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\PermissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Permission
{
    use EntityTimestampTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['permission:read'])]
    private ?int $id = null;
    #[Groups(['permission:read', 'permission:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;
    #[Groups(['permission:write', 'permission:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;
    #[Groups(['permission:read', 'permission:write'])]
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $beginningHour = null;
    #[Groups(['permission:read', 'permission:write'])]
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endTime = null;
    #[Groups(['permission:read', 'permission:write'])]
    #[ORM\Column(length: 255)]
    private ?string $naturePermission = null;
    #[Groups(['permission:read', 'permission:write'])]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'permissions')]
    #[Groups(['permission:read'])]
    private ?User $userPermissions = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBeginningHour(): ?\DateTimeInterface
    {
        return $this->beginningHour;
    }

    public function setBeginningHour(\DateTimeInterface $beginningHour): static
    {
        $this->beginningHour = $beginningHour;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getNaturePermission(): ?string
    {
        return $this->naturePermission;
    }

    public function setNaturePermission(string $naturePermission): static
    {
        $this->naturePermission = $naturePermission;

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

    public function getUserPermissions(): ?User
    {
        return $this->userPermissions;
    }

    public function setUserPermissions(?User $userPermissions): static
    {
        $this->userPermissions = $userPermissions;

        return $this;
    }
}
