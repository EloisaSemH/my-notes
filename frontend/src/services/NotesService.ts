import api from './api'

export const listNotes = () => api.get('/notes')

export const getNote = (uuid: string) => api.get(`/notes/${uuid}`)

export const createNote = (note: {
  title: string
  content: string
  color?: string
}) => api.post('/notes', note)

export const updateNote = (uuid: string, note: {
  title: string
  content: string
  color?: string
}) => api.put(`/notes/${uuid}`, note)

export const deleteNote = (uuid: string) => api.delete(`/notes/${uuid}`)
