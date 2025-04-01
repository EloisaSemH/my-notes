<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'
import {
  listNotes,
  createNote,
  updateNote,
  deleteNote,
} from '@/services/NotesService'
import NoteForm from '@/components/NoteForm.vue'

const notes = ref<any[]>([])
const newNote = ref({ title: '', content: '', color: 'FFC107' })
const editingNotes = ref<Record<string, boolean>>({})
const expandedNotes = ref<Record<string, boolean>>({})
const addingNote = ref(false)
const overflowingNotes = ref<Record<string, boolean>>({})

const fetchNotes = async () => {
  const { data } = await listNotes()
  notes.value = data
  nextTick(() => {
    notes.value.forEach(note => checkOverflow(note.uuid))
  })
}

const checkOverflow = (uuid: string) => {
  nextTick(() => {
    const el = document.querySelector(`#note-content-${uuid}`)
    if (el) {
      const lineHeight = parseFloat(getComputedStyle(el).lineHeight || '24')
      const maxHeight = lineHeight * 5
      overflowingNotes.value[uuid] = el.scrollHeight > maxHeight
    }
  })
}

const addNote = async () => {
  if (!newNote.value.title || !newNote.value.content) return
  await createNote(newNote.value)
  newNote.value = { title: '', content: '', color: '' }
  await fetchNotes()
}

const saveNote = async (note: any) => {
  await updateNote(note.uuid, {
    title: note.title,
    content: note.content,
    color: note.color,
  })
  await fetchNotes()
}

const removeNote = async (uuid: string) => {
  await deleteNote(uuid)
  await fetchNotes()
}

const enableEdit = (uuid: string) => {
  editingNotes.value[uuid] = true
}

const disableEdit = (uuid: string) => {
  editingNotes.value[uuid] = false
}

const handleClickOutside = (event: MouseEvent) => {
  const path = event.composedPath()
  const cards = document.querySelectorAll('.note-card')
  let clickedInside = false

  cards.forEach(card => {
    if (path.includes(card)) {
      clickedInside = true
    }
  })

  const clickedAddNoteTrigger = path.some(
    el => el instanceof HTMLElement && el.classList?.contains('add-note-trigger')
  )

  if (!clickedInside && !clickedAddNoteTrigger) {
    editingNotes.value = {}
    addingNote.value = false
  }
}

const handleEscape = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    editingNotes.value = {}
    addingNote.value = false
  }
}

const formatDate = (date: string): string => {
  const options: Intl.DateTimeFormatOptions = {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }

  return new Intl.DateTimeFormat('pt-PT', options).format(new Date(date))
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleEscape)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleEscape)
})

onMounted(fetchNotes)
</script>

<template>
  <v-container class="py-6">
    <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-4">
      <div class="mb-4 break-inside-avoid">
        <v-card v-if="addingNote" class="pa-4 note-card shadow-md" :style="{ backgroundColor: '#' + newNote.color }" variant="flat">
          <NoteForm
            v-model:title="newNote.title"
            v-model:content="newNote.content"
            v-model:color="newNote.color"
            :editing="false"
            @submit="addNote"
          />
        </v-card>

        <v-card
          v-else
          class="d-flex align-center justify-center pa-4 note-card add-note-trigger shadow-md"
          variant="outlined"
          style="cursor: pointer; height: 100%; min-height: 180px;"
          @click="addingNote = true"
        >
          <v-icon size="48">mdi-plus</v-icon>
        </v-card>
      </div>

      <div
        v-for="note in notes"
        :key="note.uuid"
        class="mb-4 break-inside-avoid"
      >
        <v-card class="pa-4 note-card shadow-md" :style="{ backgroundColor: '#' + (note.color || 'FFC107') }" variant="flat">
          <v-card-title v-if="!editingNotes[note.uuid]" @click="enableEdit(note.uuid)" class="text-h6">
            {{ note.title }}
          </v-card-title>
          <v-card-subtitle class="text-caption" v-if="!editingNotes[note.uuid]">
            {{ formatDate(note.updated_at) }}
          </v-card-subtitle>
          <v-card-text
            v-if="!editingNotes[note.uuid]"
            class="cursor-pointer"
            style="white-space: pre-line;"
          >
            <div
              :id="`note-content-${note.uuid}`"
              :class="!expandedNotes[note.uuid] ? 'line-clamp-5' : ''"
              @click="enableEdit(note.uuid)"
            >
              {{ note.content }}
            </div>
            <v-btn
              v-if="overflowingNotes[note.uuid]"
              size="small"
              variant="text"
              class="mt-2 float-right"
              @click="expandedNotes[note.uuid] = !expandedNotes[note.uuid]"
            >
              {{ expandedNotes[note.uuid] ? 'Show less' : 'Show more' }}
            </v-btn>
          </v-card-text>
          <NoteForm
            v-if="editingNotes[note.uuid]"
            v-model:title="note.title"
            v-model:content="note.content"
            v-model:color="note.color"
            :editing="true"
            :uuid="note.uuid"
            @submit="() => { saveNote(note); disableEdit(note.uuid) }"
            @removeNote="removeNote"
          />
        </v-card>
      </div>
    </div>
  </v-container>
</template>