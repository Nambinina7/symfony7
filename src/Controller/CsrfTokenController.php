<?php

namespace App\Controller;

use App\Entity\CsrfToken as CsrfTokenEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenController extends AbstractController
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $csrfToken = $this->csrfTokenManager->getToken('contact_item');

        return new JsonResponse(['csrf_token' => $csrfToken->getValue()]);
    }
}
