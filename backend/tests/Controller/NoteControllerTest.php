<?php

namespace App\Tests\Controller;

use App\Tests\Factory\NoteFactory;
use App\Tests\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NoteControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private $client;
    private $user;
    private $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $password = 'sometimesb4dthingst4ak&placewh3reg00dth!ngsgo';
        $this->user = UserFactory::createOne(['password_hash' => $password]);
        $this->client->request('POST', '/api/login', content: json_encode([
            'email' => $this->user->getEmail(),
            'password' => $password
        ]));

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $data['token'];
    }

    public function testListNotes(): void
    {
        NoteFactory::createMany(3, ['user' => $this->user]);
        NoteFactory::createOne();

        $this->client->request('GET', '/api/notes', server: [
            'HTTP_AUTHORIZATION' => "Bearer {$this->token}"
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertCount(3, json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testListNotesEmpty(): void
    {
        NoteFactory::createMany(3);

        $this->client->request('GET', '/api/notes', server: [
            'HTTP_AUTHORIZATION' => "Bearer {$this->token}"
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertCount(0, json_decode($this->client->getResponse()->getContent(), true));
    }

    public function testCreateNote(): void
    {
        $payload = [
            'title' => 'My todo',
            'content' => 'Go to Billie Eilish concert',
            'color' => 'FF00FF'
        ];

        $this->client->request(
            'POST',
            '/api/notes',
            server: ['HTTP_AUTHORIZATION' => "Bearer {$this->token}"],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note created', $this->client->getResponse()->getContent());
    }

    public function testCreateNoteNoTitle(): void
    {
        $payload = [
            'content' => 'Go to Billie Eilish concert',
        ];

        $this->client->request(
            'POST',
            '/api/notes',
            server: ['HTTP_AUTHORIZATION' => "Bearer {$this->token}"],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('title', $this->client->getResponse()->getContent());
    }

    public function testCreateNoteNoContent(): void
    {
        $payload = [
            'title' => 'My todo',
        ];

        $this->client->request(
            'POST',
            '/api/notes',
            server: ['HTTP_AUTHORIZATION' => "Bearer {$this->token}"],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('content', $this->client->getResponse()->getContent());
    }

    public function testCreateNoteInvalidColor(): void
    {
        $payload = [
            'title' => 'My todo',
            'content' => 'Go to Billie Eilish concert',
            'color' => 'GHIJKL'
        ];

        $this->client->request(
            'POST',
            '/api/notes',
            server: ['HTTP_AUTHORIZATION' => "Bearer {$this->token}"],
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('color', $this->client->getResponse()->getContent());
    }

    public function testShowNote(): void
    {
        $note = NoteFactory::createOne(['user' => $this->user]);

        $this->client->request('GET', "/api/notes/{$note->getUuid()}", server: [
            'HTTP_AUTHORIZATION' => "Bearer {$this->token}"
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString($note->getTitle(), $this->client->getResponse()->getContent());
    }

    public function testUpdateNote(): void
    {
        $note = NoteFactory::createOne(['user' => $this->user]);

        $updatePayload = [
            'title' => 'My todo updated',
            'content' => 'Go to Billie Eilish concert in São Paulo',
            'color' => '123456'
        ];

        $this->client->request(
            'PUT',
            "/api/notes/{$note->getUuid()}",
            server: ['HTTP_AUTHORIZATION' => "Bearer {$this->token}", 'CONTENT_TYPE' => 'application/json'],
            content: json_encode($updatePayload)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note updated', $this->client->getResponse()->getContent());
    }

    public function testDeleteNote(): void
    {
        $note = NoteFactory::createOne(['user' => $this->user]);

        $this->client->request('DELETE', "/api/notes/{$note->getUuid()}", server: [
            'HTTP_AUTHORIZATION' => "Bearer {$this->token}"
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note deleted', $this->client->getResponse()->getContent());
    }
}
