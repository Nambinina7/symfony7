<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\JWTService;
use App\Services\MailerServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SendEmailForgotController extends AbstractController
{
    public function __construct(
        private readonly JWTService $jwtService,
        private readonly UserRepository $userRepository,
        private readonly MailerServices $mailerServices,
        private readonly string $app_url_front,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }
    #[Route('/api/employee/send/email', name: 'send_email', methods: ['POST'])]
    public function forgotPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['error' => 'Email is required'], 400);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 404);
        }

        $user->setLastChangeRequest(new \DateTime('now +2 hours'));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = $this->jwtService->createToken($user);

        $resetUrl = "{$this->app_url_front}/forgot-password?token={$token->toString()}";

        $message = User::HTML_CONTENT_MESSAGE;

        $htmlContent = "<p> $message : <a href=\"$resetUrl\">$resetUrl</a></p>";

        $this->mailerServices->sendEmail(
            $user->getEmail(),
            'Change password',
            $htmlContent
        );

        return new JsonResponse(['message' => 'Email sent successfully'], 200);
    }
}
