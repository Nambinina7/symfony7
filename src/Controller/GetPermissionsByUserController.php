<?php

namespace App\Controller;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetPermissionsByUserController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            throw new AccessDeniedException('You must be logged in to access this resource.');
        }

        $permissions = $entityManager->getRepository(Permission::class)->findBy([
            'userPermissions' => $currentUser,
        ]);

        return $this->json($permissions, 200, [], ['groups' => ['permission:read']]);
    }
}
