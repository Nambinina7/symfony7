<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserUpdateController extends AbstractController
{
    public function __construct(
        private readonly JWTService $jwtService,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token_update_password'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

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
}
