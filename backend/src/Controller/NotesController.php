<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Serializer\NotesSerializerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Uid\Uuid;

#[Route('/api/notes')]
class NotesController extends AbstractController
{
    use NotesSerializerTrait;

    #[Route('', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $notes = $em->getRepository(Notes::class)->findBy([], ['created_at' => 'DESC']);
        $data = $this->serializeNotes($notes);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $note = new Notes();
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
        $note = $em->getRepository(Notes::class)->findOneByUuid($uuid);
        $data = $this->serializeNote($note);
        return $this->json($data);
    }

    #[Route('/{uuid}', methods: ['PUT'])]
    public function update(string $uuid, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $note = $em->getRepository(Notes::class)->findOneByUuid($uuid);
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
        $note = $em->getRepository(Notes::class)->findOneByUuid($uuid);
        $em->remove($note);
        $em->flush();

        return $this->json(['status' => 'Note deleted'], 200);
    }
}
