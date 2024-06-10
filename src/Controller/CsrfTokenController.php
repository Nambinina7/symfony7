<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class CsrfTokenController extends AbstractController
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly Security $security,
    ) {

    }

    #[Route('/contact/csrf-token', name: 'api_csrf_token', methods: ['GET'])]
    public function getCsrfToken(): JsonResponse
    {
        $user = $this->security->getUser();

        if ($user && $this->isGranted('ROLE_API_USER')) {

            try {
                $token = $this->csrfTokenManager->getToken('contact_item');

                if ($token instanceof CsrfToken) {
                    return new JsonResponse(['token' => $token->getValue()]);
                }

                return new JsonResponse(['error' => 'Generated token is not an instance of CsrfToken'], 500);
            } catch (\Exception $e) {
                return new JsonResponse(['error' => 'Exception: ' . $e->getMessage()], 500);
            }
        }

        return new JsonResponse(['error' => 'User not authenticated or does not have the required role'], 401);
    }
}
