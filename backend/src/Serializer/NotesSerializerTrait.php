<?php

namespace App\Serializer;

use App\Entity\Notes;

trait NotesSerializerTrait
{
    /**
     * @return array<string, mixed>
     */
    public function serializeNotes(array $notes): array
    {
        return array_map(fn(Notes $note) => $this->serializeNote($note), $notes);
    }

    /**
     * @return array<string, mixed>
     */
    public function serializeNote(Notes $note): array
    {
        return [
            'uuid' => $note->getUuid(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'color' => $note->getColor(),
            'is_pinned' => $note->getIsPinned(),
            'is_archived' => $note->getIsArchived(),
            'source' => $note->getSource(),
            'created_at' => $note->getCreatedAt()->format('c'),
            'updated_at' => $note->getUpdatedAt()->format('c'),
        ];
    }
}