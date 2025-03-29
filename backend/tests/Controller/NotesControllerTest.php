<?php

namespace App\Tests\Controller;

use App\Tests\Factory\NotesFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotesControllerTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    public function testListNotes(): void
    {
        $client = static::createClient();

        NotesFactory::createMany(3);

        $client->request('GET', '/api/notes');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertCount(3, json_decode($client->getResponse()->getContent(), true));
    }

    public function testCreateNote(): void
    {
        $client = static::createClient();

        $payload = [
            'title' => 'My todo',
            'content' => 'Go to Billie Eilish concert',
            'color' => 'FF00FF'
        ];

        $client->request(
            'POST',
            '/api/notes',
            content: json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note created', $client->getResponse()->getContent());
    }

    public function testShowNote(): void
    {
        $client = static::createClient();

        $note = NotesFactory::createOne();

        $client->request('GET', "/api/notes/{$note->getUuid()}");
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString($note->getTitle(), $client->getResponse()->getContent());
    }

    public function testUpdateNote(): void
    {
        $client = static::createClient();

        $note = NotesFactory::createOne();

        $updatePayload = [
            'title' => 'My todo updated',
            'content' => 'Go to Billie Eilish concert in São Paulo',
            'color' => '123456'
        ];

        $client->request(
            'PUT',
            "/api/notes/{$note->getUuid()}",
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($updatePayload)
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note updated', $client->getResponse()->getContent());
    }

    public function testDeleteNote(): void
    {
        $client = static::createClient();

        $note = NotesFactory::createOne();

        $client->request('DELETE', "/api/notes/{$note->getUuid()}");
        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $this->assertStringContainsString('Note deleted', $client->getResponse()->getContent());
    }
}
