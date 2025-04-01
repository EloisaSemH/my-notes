<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $this->em = $em;

        $this->userRepository = $container->get(UserRepository::class);

        foreach ($this->userRepository->findAll() as $user) {
            $this->em->remove($user);
        }

        $this->em->flush();
    }

    public function testResetPasswordController(): void
    {
        $user = UserFactory::createOne();

        $this->client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Reset your password');
        $this->client->submitForm('Send password reset email', [
            'reset_password_request_form[email]' => $user->getEmail(),
        ]);

        self::assertEmailCount(1);

        self::assertCount(1, $messages = $this->getMailerMessages());

        self::assertEmailAddressContains($messages[0], 'from', 'mailer@your-domain.com');
        self::assertEmailAddressContains($messages[0], 'to', $user->getEmail());
        self::assertEmailTextBodyContains($messages[0], 'This link will expire in 1 hour.');

        self::assertResponseRedirects('/reset-password/check-email');

        // Test check email landing page shows correct "expires at" time
        $crawler = $this->client->followRedirect();

        self::assertPageTitleContains('Password Reset Email Sent');
        self::assertStringContainsString('This link will expire in 1 hour', $crawler->html());

        $email = $messages[0]->toString();
        preg_match('#(/reset-password/reset/[a-zA-Z0-9]+)#', $email, $resetLink);

        $this->client->request('GET', $resetLink[1]);

        self::assertResponseRedirects('/reset-password/reset');

        $this->client->followRedirect();

        $this->client->submitForm('Reset password', [
            'change_password_form[plainPassword][first]' => 'newStrongPassword',
            'change_password_form[plainPassword][second]' => 'newStrongPassword',
        ]);

        $user = $this->userRepository->findOneBy(['email' => $user->getEmail()]);

        self::assertInstanceOf(User::class, $user);

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        self::assertTrue($passwordHasher->isPasswordValid($user, 'newStrongPassword'));
    }
}
