import { createContext, useContext, useEffect, useMemo, useState } from 'react'
import api from '../api/client'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [token, setToken] = useState(() => localStorage.getItem('his_token'))
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(Boolean(token))

  // Restore the session from a saved token on first load. No synchronous
  // setState here — loading already starts false without a token.
  useEffect(() => {
    if (!token) {
      return
    }
    let cancelled = false
    api
      .get('/me')
      .then((res) => {
        if (cancelled) return
        setUser(res.data.user)
      })
      .catch(() => {
        if (cancelled) return
        localStorage.removeItem('his_token')
        setToken(null)
        setUser(null)
      })
      .finally(() => {
        if (!cancelled) setLoading(false)
      })
    return () => {
      cancelled = true
    }
  }, [token])

  const login = async (role, credentials) => {
    const res = await api.post(`/login/${role}`, credentials)
    localStorage.setItem('his_token', res.data.token)
    setToken(res.data.token)
    setUser(res.data.user)
    return res.data
  }

  const logout = async () => {
    try {
      await api.post('/logout')
    } catch {
      // token may already be invalid - ignore
    }
    localStorage.removeItem('his_token')
    setToken(null)
    setUser(null)
  }

  // Profile page updates dispatch this event to refresh the session user
  useEffect(() => {
    const handler = (event) => setUser(event.detail)
    window.addEventListener('profile-updated', handler)
    return () => window.removeEventListener('profile-updated', handler)
  }, [])

  const value = useMemo(
    () => ({ token, user, loading, login, logout, isAdmin: user?.role === 'admin', isDoctor: user?.role === 'doctor' }),
    [token, user, loading],
  )

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

export const useAuth = () => useContext(AuthContext)
