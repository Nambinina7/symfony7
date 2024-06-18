<?php

namespace App\Controller;

use App\Entity\Permission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetPermissionsByUserAction extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $currentUser = $this->getUser();

        $permissions = $entityManager->getRepository(Permission::class)->findBy([
            'userPermissions' => $currentUser,
        ]);

        return $this->json($permissions, 200, [], ['groups' => ['permission:read']]);
    }
}
