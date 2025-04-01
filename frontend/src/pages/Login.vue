<script setup lang="ts">
import { ref } from 'vue'
import router from '../router'
import { setToken } from '../stores/auth'

const form = ref({
  email: '',
  password: '',
})

const formRef = ref()

const login = async () => {
  try {
    const response = await fetch('http://localhost:8080/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value),
    })

    if (!response.ok) {
      const error = await response.json()
      alert(error.message || 'Error logging in')
      return
    }

    const data = await response.json()
    setToken(data.token)
    router.push('/')
  } catch (error) {
    console.error(error)
    alert('Error connecting to the server.')
  }
}
</script>

<template>
  <v-container class="d-flex justify-center align-center fill-height">
    <v-card class="pa-6" width="500">
      <v-card-title class="text-h5 text-center">Login</v-card-title>
      <v-form @submit.prevent="login" ref="formRef">
        <v-text-field
          v-model="form.email"
          label="Email"
          required
          type="email"
          prepend-inner-icon="mdi-email"
        />
        <v-text-field
          v-model="form.password"
          label="Password"
          type="password"
          required
          prepend-inner-icon="mdi-lock"
        />
        <v-btn type="submit" color="primary" block class="mt-4">Login</v-btn>
      </v-form>
      <v-card-actions class="justify-center mt-4">
        <span>Don't have an account?</span>
        <router-link to="/register" class="ml-1 text-blue">Register</router-link>
      </v-card-actions>
    </v-card>
  </v-container>
</template>