<script setup lang="ts">
const props = defineProps<{
  title: string
  content: string
  color: string
  editing?: boolean
  uuid?: string
}>()

const emit = defineEmits<{
  (e: 'update:title', value: string): void
  (e: 'update:content', value: string): void
  (e: 'update:color', value: string): void
  (e: 'submit'): void
  (e: 'removeNote', uuid: string): void
}>()

import { computed } from 'vue'

const title = computed({
  get: () => props.title,
  set: value => emit('update:title', value)
})

const content = computed({
  get: () => props.content,
  set: value => emit('update:content', value)
})

const color = computed({
  get: () => props.color,
  set: value => emit('update:color', value)
})

const uuid = computed(() => props.uuid)

const colors = ['FFC107', 'F44336', 'E91E63', '9C27B0', '673AB7', '2196F3', '009688', '4CAF50', '607D8B']

</script>
<template>
  <v-card
    class="pa-4 note-card shadow-md"
    :style="{ backgroundColor: '#' + color }"
    variant="flat"
  >
    <v-text-field
      v-model="title"
      :label="editing ? 'Title' : 'New note'"
      variant="outlined"
    />
    <v-textarea
      v-model="content"
      label="Content"
      variant="outlined"
      auto-grow
    />
    <div class="d-flex flex-wrap gap-2 mt-3">
      <v-btn
        v-for="c in colors"
        :key="c"
        :color="'#' + c"
        class="ma-0"
        elevation="2"
        width="28"
        height="28"
        @click="$emit('update:color', c)"
      ></v-btn>
    </div>
    <div class="d-flex justify-end mt-3">
      <v-btn
        size="small"
        color="red-darken-1"
        class="mt-3 mr-2"
        @click="$emit('removeNote', uuid)"
        icon="$vuetify"
      >
        <v-icon size="20">mdi-delete</v-icon>
      </v-btn>
      <v-btn
        :color="editing ? 'green-darken-2' : 'blue-darken-2'"
        class="mt-3"
        size="small"
        @click="$emit('submit')"
        icon="$vuetify"
      >
        <v-icon size="20">{{ editing ? 'mdi-check' : 'mdi-plus' }}</v-icon>
      </v-btn>
    </div>
  </v-card>
</template>