<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;

class ResetPasswordEmailSender
{
    public function __construct(
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function sendResetEmail(User $user, string $resetToken): void
    {
        $resetUrl = $this->urlGenerator->generate('app_reset_password', [
            'token' => $resetToken
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('no-reply@mynotesapp.com')
            ->to($user->getEmail())
            ->subject('Password reset')
            ->html("
                <p>Hi, {$user->getName()}!</p>
                <p>To reset your password, click the link below:</p>
                <a href='$resetUrl'>$resetUrl</a>
                <p>If you didn't request this, ignore this email.</p>
            ");

        $this->mailer->send($email);
    }
}