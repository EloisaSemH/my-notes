<?php

namespace App\Controller;

use App\Entity\Note;
use App\Serializer\NoteSerializerTrait;
use App\Validator\NoteValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[Route('/api/notes')]
#[IsGranted('ROLE_USER')]
class NoteController extends AbstractController
{
    use NoteSerializerTrait;

    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        $notes = $em->getRepository(Note::class)->findBy(['user' => $user], ['created_at' => 'DESC']);
        $data = $this->serializeNotes($notes);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, NoteValidator $noteValidator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = $noteValidator->validate($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }
        $user = $this->getUser();

        $note = new Note();
        $note->setUser($user);
        $note->setUuid(Uuid::v4()->toRfc4122());
        $note->setTitle($data['title'] ?? '');
        $note->setContent($data['content'] ?? '');
        $note->setColor($data['color'] ?? null);
        $note->setIsPinned($data['is_pinned'] ?? false);
        $note->setIsArchived($data['is_archived'] ?? false);
        $note->setSource('php');
        $now = new \DateTimeImmutable();
        $note->setCreatedAt($now);
        $note->setUpdatedAt($now);

        $em->persist($note);
        $em->flush();

        return $this->json(['status' => 'Note created'], 201);
    }

    #[Route('/{uuid}', methods: ['GET'])]
    public function show(string $uuid, EntityManagerInterface $em): JsonResponse
    {
        $note = $em->getRepository(Note::class)->findOneByUuid($uuid);
        if (!$note || $note->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Note not found'], 404);
        }
        $data = $this->serializeNote($note);
        return $this->json($data);
    }

    #[Route('/{uuid}', methods: ['PUT'])]
    public function update(string $uuid, Request $request, EntityManagerInterface $em, NoteValidator $noteValidator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $errors = $noteValidator->validate($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }
        $note = $em->getRepository(Note::class)->findOneByUuid($uuid);
        if (!$note || $note->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Note not found'], 404);
        }
        $note->setTitle($data['title'] ?? '');
        $note->setContent($data['content'] ?? '');
        $note->setColor($data['color'] ?? null);
        $note->setIsPinned($data['is_pinned'] ?? false);
        $note->setIsArchived($data['is_archived'] ?? false);
        $note->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->json(['status' => 'Note updated'], 200);
    }

    #[Route('/{uuid}', methods: ['DELETE'])]
    public function delete(string $uuid, EntityManagerInterface $em): JsonResponse
    {
        $note = $em->getRepository(Note::class)->findOneByUuid($uuid);
        if (!$note || $note->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Note not found'], 404);
        }
        $em->remove($note);
        $em->flush();

        return $this->json(['status' => 'Note deleted'], 200);
    }
}
