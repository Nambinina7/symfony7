<?php

namespace App\Controller;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetPermissionsByUserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getPermissions(): JsonResponse
    {
        $currentUser = $this->getUserOrThrow();

        $permissions = $this->entityManager->getRepository(Permission::class)->findBy([
            'userPermissions' => $currentUser,
        ]);

        return $this->json($permissions, 200, [], ['groups' => ['permission:read']]);
    }

    public function countPermissions(): JsonResponse
    {
        $currentUser = $this->getUserOrThrow();

        $permissions = $this->entityManager->getRepository(Permission::class)->findBy([
            'userPermissions' => $currentUser,
        ]);

        $totalTime = $this->calculateTotalPermissionTime($permissions);

        return $this->json(['total_time' => round($totalTime)], 200);
    }

    private function getUserOrThrow()
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        return $currentUser;
    }

    private function calculateTotalPermissionTime($permissions): float
    {
        $totalTime = 0;

        foreach ($permissions as $permission) {
            $beginningHour = $permission->getBeginningHour();
            $endTime = $permission->getEndTime();

            $difference = $endTime->diff($beginningHour);
            $totalTime += $difference->h + ($difference->i / 60) + ($difference->s / 3600);
        }

        return $totalTime;
    }
}
