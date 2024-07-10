<?php

namespace App\Services;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordService
{
    public function __construct(
        private readonly JWTService $jwtService,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function updatePassword(string $token, string $newPassword): JsonResponse
    {
        if (!$token || !$newPassword) {
            return new JsonResponse(['error' => 'Token and new password are required'], 400);
        }

        $parsedToken = $this->jwtService->parseToken($token);
        if (!$parsedToken) {
            return new JsonResponse(['error' => 'Invalid token'], 400);
        }

        $email = $parsedToken->claims()->get('email');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $lastChangeRequest = $user->getLastChangeRequest();

        if (!$this->jwtService->validateToken($parsedToken, $lastChangeRequest)) {
            return new JsonResponse(['error' => 'Invalid or expired token'], 400);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Password updated successfully'], 200);
    }

    public function confirmPassword(string $token, string $password, string $confirmPassword): JsonResponse
    {
        if ($password !== $confirmPassword) {
            return new JsonResponse(['error' => 'Passwords do not match'], 400);
        }

        $passwordValidation = $this->validatePassword($password);
        if ($passwordValidation !== true) {
            return new JsonResponse(['error' => $passwordValidation], 422);
        }

        return $this->updatePassword($token, $password);
    }

    public function validatePassword(string $password): bool|string
    {
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return 'Password must contain at least one special character.';
        }

        if (!preg_match('/[0-9]/', $password)) {
            return 'Password must contain at least one number.';
        }

        return true;
    }
}
