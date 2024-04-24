<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\FaqRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FaqRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Faq
{

    use EntityTimestampTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['faq:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['faq:read'])]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['faq:read'])]
    private ?string $answer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }
}
