<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(HttpUtils $httpUtils, JWTTokenManagerInterface $jwtManager)
    {
        parent::__construct($httpUtils);
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $user = $token->getUser();

        if ($user instanceof UserInterface) {
            $jwt = $this->jwtManager->create($user);
            $data = [
                'token' => $jwt,
                'roles' => $user->getRoles(),
            ];

            return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        return parent::onAuthenticationSuccess($request, $token);
    }
}

