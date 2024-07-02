<?php

namespace App\Services;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerServices
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $default_email,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $to, string $subject, string $message): void
    {
        $email = (new Email())
            ->from($this->default_email)
            ->to($to)
            ->subject($subject)
            ->html($message);

        $this->mailer->send($email);
    }

}
