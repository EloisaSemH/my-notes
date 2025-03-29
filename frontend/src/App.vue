<script setup lang="ts">
import Home from './components/Home.vue';
import { useTheme } from 'vuetify'
import { ref, watch } from 'vue'

const theme = useTheme()
const isDark = ref(false)

watch(isDark, val => {
  theme.global.name.value = val ? 'dark' : 'light'
})

</script>

<template>
  <div :class="isDark ? 'bg-grey-darken-4' : 'bg-grey-lighten-5'" style="min-height: 100vh;">
    <header :class="isDark ? 'bg-grey-darken-4 shadow-md' : 'bg-grey-lighten-5 shadow-md'">
      <v-container class="d-flex justify-end px-6 pt-4">
        <v-switch v-model="isDark" label="Dark mode" inset hide-details />
      </v-container>
    </header>

    <main>
      <Home />
    </main>
  </div>
</template>

<style scoped></style>
