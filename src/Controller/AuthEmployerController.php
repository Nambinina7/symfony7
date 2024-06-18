<?php

namespace App\Controller;

use App\Form\LoginFormEmployeeType;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Form\LoginFormType;

class AuthEmployerController extends AbstractController
{
    private CsrfTokenManagerInterface $csrfTokenManager;
    private UserProviderInterface $userProvider;
    private FormFactoryInterface $formFactory;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, UserProviderInterface $userProvider, FormFactoryInterface $formFactory)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userProvider = $userProvider;
        $this->formFactory = $formFactory;
    }

    #[Route(path: '/api/employee/login_check', name: 'api_employee_check', methods: ['POST'])]
    public function login(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $form = $this->formFactory->create(LoginFormEmployeeType::class);
        $form->submit($data);

        $csrfTokenValue = $data['employee_connection'];
        $csrfToken = new CsrfToken('authenticate', $csrfTokenValue);
        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            return new JsonResponse(['error' => 'Invalid CSRF token'], Response::HTTP_BAD_REQUEST);
        }

        $email = $form->get('email')->getData();
        $password = $form->get('password')->getData();

        try {
            $user = $this->userProvider->loadUserByIdentifier($email);
        } catch (AuthenticationException $e) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }


        if (!in_array('ROLE_EMPLOYER', $user->getRoles())) {
            return new JsonResponse(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
