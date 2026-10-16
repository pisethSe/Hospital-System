import axios from 'axios'

/*
 * In production the API and the frontend are deployed separately, so
 * VITE_API_URL holds the backend's base URL (set at build time on the
 * hosting platform). Locally it is empty and the Vite dev proxy handles
 * /api -> http://localhost:8000.
 */
export const API_BASE = (import.meta.env.VITE_API_URL || '').replace(/\/+$/, '')

const client = axios.create({
  baseURL: `${API_BASE}/api`,
  headers: { Accept: 'application/json' },
})

client.interceptors.request.use((config) => {
  const token = localStorage.getItem('his_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

client.interceptors.response.use(
  (response) => response,
  (error) => {
    // Expired / invalid token -> back to the login page
    if (error.response?.status === 401 && !String(error.config?.url).includes('/login')) {
      localStorage.removeItem('his_token')
      if (window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }
    return Promise.reject(error)
  },
)

export default client

/** Public URL for files served by Laravel's storage disk (profile photos). */
export function storageUrl(path) {
  return path ? `${API_BASE}/storage/${path}` : null
}
