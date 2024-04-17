<?php

namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait EntityTimestampTrait
{
    #[ORM\Column(nullable: true)]
    #[Groups(['banner:read', 'user:read'])]
    private ?\DateTime $createdAt = null;


    #[ORM\Column(nullable: true)]
    #[Groups(['banner:read', 'user:read'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreateAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}