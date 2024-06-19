<?php

namespace App\Controller;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HolidayByUserController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        $holidays = $currentUser->getHolydays();

        return $this->json($holidays, 200, [], ['groups' => ['holyday:read']]);
    }
}
