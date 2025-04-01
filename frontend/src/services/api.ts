import axios from 'axios'
import { getToken } from '@/stores/auth'


const api = axios.create({
  baseURL: 'http://localhost:8080/api',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${getToken()}`,
  },
})

export default api