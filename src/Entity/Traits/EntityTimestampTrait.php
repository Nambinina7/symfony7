<?php

namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait EntityTimestampTrait
{
    #[ORM\Column(nullable: true)]
    #[Groups([
        'banner:read', 'user:read',
        'technology:read', 'faq:read',
        'service:read', 'section:read',
        'contact:create', 'permission:read',
        'permission:read', 'holyday:read',
        'holyday:write'])]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\Column(nullable: true)]
    #[Groups([
        'banner:read', 'user:read',
        'technology:read', 'faq:read',
        'service:read', 'section:read',
        'contact:create', 'permission:read',
        'permission:read', 'holyday:read',
        'holyday:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreateAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
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
