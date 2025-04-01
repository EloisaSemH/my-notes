import { ref } from 'vue'

export const isLoggedIn = ref(!!localStorage.getItem('token'))

export function setToken(token: string) {
  localStorage.setItem('token', token)
  isLoggedIn.value = true
}

export function removeToken() {
  localStorage.removeItem('token')
  isLoggedIn.value = false
}

export function getToken() {
  return localStorage.getItem('token')
}
