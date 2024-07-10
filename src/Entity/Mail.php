<?php

namespace App\Entity;

use App\Entity\Traits\EntityTimestampTrait;
use App\Repository\MailRepository;
use App\Services\MailerServices;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Mail
{
    use EntityTimestampTrait;

    public const SEND_PASSWORD_RESET_LINK = "Change password";
    public const FORGOT_PASSWORD_RESET_LINK = "Forgot password";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(length: 255)]
    private ?string $body = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $parameters = [];

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

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(?array $parameters): static
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function getParametersFormatted(): ?string
    {
        return $this->parameters ? implode(', ', $this->parameters) : null;
    }

    public function sendResetPasswordEmail(MailRepository $mailRepository, MailerServices $mailerServices, User $user, string $resetUrl, string $mailName): void
    {
        $mail = $mailRepository->findOneBy(['name' => $mailName]);

        $message = $mail->getBody();

        foreach ($mail->getParameters() as $index => $parameter) {
            if ($index === 0) {
                $message = str_replace($parameter, $resetUrl, $message);
            }

            if ($index === 1) {
                $mailerServices->sendEmail(
                    $user->getEmail(),
                    $parameter,
                    sprintf('%s : %s', $mail->getSubject(), $message)
                );
            }
        }
    }
}
