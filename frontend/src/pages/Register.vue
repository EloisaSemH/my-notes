
<script setup lang="ts">
import { ref } from 'vue'
import router from '../router'

const form = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const formRef = ref()

const register = async () => {
  if (form.value.password !== form.value.confirmPassword) {
    alert('The passwords do not match')
    return
  }

  try {
    delete form.value.confirmPassword
    const response = await fetch('http://localhost:8080/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value),
    })

    if (!response.ok) {
      const error = await response.json()
      alert(error.message || 'Error registering')
      return
    }

    alert('Successfully registered!')
    router.push('/login')
  } catch (error) {
    console.error(error)
    alert('Error connecting to the server.')
  }
}
</script>
<template>
  <v-container class="d-flex justify-center align-center fill-height">
    <v-card class="pa-6" width="500">
      <v-card-title class="text-h5 text-center">Create Account</v-card-title>
      <v-form @submit.prevent="register" ref="formRef">
        <v-text-field
          v-model="form.name"
          label="Name"
          required
          prepend-inner-icon="mdi-account"
        />
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
        <v-text-field
          v-model="form.confirmPassword"
          label="Confirm Password"
          type="password"
          required
          prepend-inner-icon="mdi-lock-check"
        />
        <v-btn type="submit" color="primary" block class="mt-4">Register</v-btn>
      </v-form>
      <v-card-actions class="justify-center mt-4">
        <span>Already have an account?</span>
        <router-link to="/login" class="ml-1 text-blue">Login</router-link>
      </v-card-actions>
    </v-card>
  </v-container>
</template>
