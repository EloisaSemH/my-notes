<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Service\ResetPasswordEmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Validator\RegisterValidator;
use App\Validator\LoginValidator;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserRepository $userRepository, RegisterValidator $registerValidator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = $registerValidator->validate($data);

        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $userRepository->save($data);

        return $this->json(['message' => 'User registered successfully'], 201);
    }

    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtManager, LoginValidator $loginValidator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = $loginValidator->validate($data);

        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $user = $userRepository->findOneByEmail($data['email']);

        if (!$user || !$passwordHasher->isPasswordValid($user, $data['password'])) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt(),
                'updatedAt' => $user->getUpdatedAt(),
            ],
        ], 200);
    }

    #[Route('/api/forgot-password', name: 'app_forgot_password', methods: ['POST'])]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        ResetPasswordHelperInterface $resetPasswordHelper,
        ResetPasswordEmailSender $emailSender
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['email'])) {
            return $this->json(['error' => 'Email is required'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $resetToken = $resetPasswordHelper->generateResetToken($user);

        $emailSender->sendResetEmail($user, $resetToken->getToken());

        return $this->json(['message' => 'Recovery email sent']);
    }

    #[Route('/api/reset-password/{token}', name: 'app_reset_password', methods: ['POST'])]
    public function resetPassword(
        string $token,
        Request $request,
        ResetPasswordHelperInterface $resetPasswordHelper,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['password'])) {
            return $this->json(['error' => 'Password is required'], 400);
        }

        try {
            $user = $resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Invalid or expired token'], 400);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        $resetPasswordHelper->removeResetRequest($token);

        return $this->json(['message' => 'Password has been reset']);
    }
}
