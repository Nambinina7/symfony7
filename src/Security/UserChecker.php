<?php

namespace App\Security;

use App\Entity\ApiUser;
use App\Entity\User;
use App\Services\RoleService;
use ContainerWowXODm\getUserRepositoryService;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!in_array(ApiUser::ROLE_API_USER, $user->getRoles(), true)) {
            throw new CustomUserMessageAuthenticationException(ApiUser::AUTHENTICATION_EXCEPTION_MESSAGE);
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // TODO: Implement checkPostAuth() method.
    }
}
