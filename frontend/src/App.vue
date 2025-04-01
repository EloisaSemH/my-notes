<script setup lang="ts">
import { useTheme } from 'vuetify'
import { ref, watch } from 'vue'
import { isLoggedIn, removeToken } from '@/stores/auth'
import router from '@/router'

const theme = useTheme()
const isDark = ref(false)

if (localStorage.getItem('isDark')) {
  isDark.value = localStorage.getItem('isDark') === 'true'
  theme.global.name.value = isDark.value ? 'dark' : 'light'
}

watch(isDark, val => {
  theme.global.name.value = val ? 'dark' : 'light'
  localStorage.setItem('isDark', val.toString())
})

const logout = () => {
  removeToken()
}

const navigateTo = (path: string) => {
  router.push(path)
}
</script>

<template>
  <div :class="isDark ? 'bg-grey-darken-4' : 'bg-grey-lighten-5'" style="min-height: 100vh;">
    <header :class="isDark ? 'bg-grey-darken-4 shadow-md' : 'bg-grey-lighten-5 shadow-md'">
      <v-container class="d-flex justify-start align-center px-6 pt-4">
        <img src="./assets/logo.svg" alt="Logo" style="max-width: 40px; max-height: 40px;" class="mr-4 cursor-pointer" @click="navigateTo('/')" />
        <v-btn to="/" variant="text" class="text-capitalize">Home</v-btn>
        <v-btn to="/login" variant="text" class="text-capitalize" v-if="!isLoggedIn">Login</v-btn>
        <v-btn to="/register" variant="text" class="text-capitalize" v-if="!isLoggedIn">Register</v-btn>
        <v-btn @click="logout" variant="text" class="text-capitalize" v-if="isLoggedIn">Logout</v-btn>
        <v-container class="d-flex justify-end align-center">
          <v-switch v-model="isDark" label="Dark mode" inset hide-details />
        </v-container>
      </v-container>
    </header>

    <main>
      <router-view />
    </main>
  </div>
</template>

<style scoped></style>
