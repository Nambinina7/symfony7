<?php

namespace App\Controller;

use App\Services\PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdatePasswordController extends AbstractController
{
    public function __construct(
        private readonly PasswordService $passwordService
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $token = $data['token_update_password'] ?? null;
        $newPassword = $data['newPassword'] ?? null;
        $confirmPassword = $data['confirmPassword'] ?? null;

        if (!$newPassword || !$confirmPassword) {
            return new JsonResponse(['error' => 'New password and confirm password are required'], 400);
        }

        return $this->passwordService->confirmPassword($token, $newPassword, $confirmPassword);
    }
}
