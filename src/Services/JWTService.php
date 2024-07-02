<?php

namespace App\Services;

use App\Entity\User;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Plain;
use DateTimeImmutable;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;

class JWTService
{
    private Configuration $configuration;
    private string $appUrl;

    public function __construct(string $secretKey, string $appUrl)
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($secretKey)
        );
        $this->appUrl = $appUrl;
    }

    public function createToken(User $user): Plain
    {
        $now = new DateTimeImmutable();
        return $this->configuration->builder()
            ->issuedBy($this->appUrl)
            ->issuedAt($now)
            ->withClaim('email', $user->getEmail())
            ->withClaim('firstName', $user->getFirstName())
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());
    }

    public function parseToken(string $token): ?Plain
    {
        try {
            return $this->configuration->parser()->parse($token);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function validateToken(Plain $token, \DateTimeInterface $lastChangeRequest): bool
    {
        $clock = new SystemClock(new \DateTimeZone('UTC'));
        $constraints = [
            new IssuedBy($this->appUrl),
            new LooseValidAt($clock),
        ];

        $tokenIsValid = $this->configuration->validator()->validate($token, ...$constraints);

        if (!$tokenIsValid) {
            return false;
        }

        $now = new \DateTimeImmutable();
        if ($now >= $lastChangeRequest) {
            return false;
        }
        return true;
    }
}
