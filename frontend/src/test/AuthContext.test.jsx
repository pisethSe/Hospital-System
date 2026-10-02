import { describe, expect, it, vi, beforeEach } from 'vitest'
import { renderHook, waitFor, act } from '@testing-library/react'
import { AuthProvider, useAuth } from '@/context/AuthContext'

// Mock the axios client
vi.mock('@/api/client', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
  },
}))

import api from '@/api/client'

function wrapper({ children }) {
  return <AuthProvider>{children}</AuthProvider>
}

describe('AuthContext', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    localStorage.clear()
  })

  it('starts with no user and not loading when there is no stored token', async () => {
    const { result } = renderHook(() => useAuth(), { wrapper })

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(result.current.user).toBeNull()
    expect(result.current.token).toBeNull()
    expect(api.get).not.toHaveBeenCalled()
  })

  it('restores the session from a stored token on load', async () => {
    localStorage.setItem('his_token', 'stored-token')
    api.get.mockResolvedValue({
      data: { user: { id: 1, name: 'Admin User', role: 'admin' } },
    })

    const { result } = renderHook(() => useAuth(), { wrapper })

    // loading starts true (a token exists)
    expect(result.current.loading).toBe(true)

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(api.get).toHaveBeenCalledWith('/me')
    expect(result.current.user).toEqual({ id: 1, name: 'Admin User', role: 'admin' })
    expect(result.current.isAdmin).toBe(true)
  })

  it('clears the token and user when the stored token is invalid', async () => {
    localStorage.setItem('his_token', 'expired-token')
    api.get.mockRejectedValue({ response: { status: 401 } })

    const { result } = renderHook(() => useAuth(), { wrapper })

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(result.current.user).toBeNull()
    expect(result.current.token).toBeNull()
    expect(localStorage.getItem('his_token')).toBeNull()
  })

  it('login() stores the token and user, then the session is live', async () => {
    // /me resolves with the same user the login returned (as in reality)
    api.get.mockResolvedValue({
      data: { user: { id: 4, name: 'Pheakdey', role: 'doctor' } },
    })
    api.post.mockResolvedValue({
      data: {
        token: 'new-token',
        role: 'doctor',
        user: { id: 4, name: 'Pheakdey', role: 'doctor' },
      },
    })

    const { result } = renderHook(() => useAuth(), { wrapper })
    await waitFor(() => expect(result.current.loading).toBe(false))

    let loginResult
    await act(async () => {
      loginResult = await result.current.login('doctor', { doc_number: 'pkd', password: 'pkd123' })
    })

    expect(loginResult.role).toBe('doctor')
    expect(api.post).toHaveBeenCalledWith('/login/doctor', { doc_number: 'pkd', password: 'pkd123' })
    expect(localStorage.getItem('his_token')).toBe('new-token')
    expect(result.current.user).toEqual({ id: 4, name: 'Pheakdey', role: 'doctor' })
    expect(result.current.isDoctor).toBe(true)
  })

  it('logout() revokes server-side and clears local state', async () => {
    localStorage.setItem('his_token', 'live-token')
    api.get.mockResolvedValue({ data: { user: { id: 1, name: 'Admin User', role: 'admin' } } })
    api.post.mockResolvedValue({ data: { message: 'Logged out.' } })

    const { result } = renderHook(() => useAuth(), { wrapper })
    await waitFor(() => expect(result.current.user).not.toBeNull())

    await act(async () => {
      await result.current.logout()
    })

    expect(api.post).toHaveBeenCalledWith('/logout')
    expect(result.current.user).toBeNull()
    expect(result.current.token).toBeNull()
    expect(localStorage.getItem('his_token')).toBeNull()
  })

  it('refreshes the session user when the profile page dispatches profile-updated', async () => {
    localStorage.setItem('his_token', 'live-token')
    api.get.mockResolvedValue({ data: { user: { id: 1, name: 'Old Name', role: 'admin' } } })

    const { result } = renderHook(() => useAuth(), { wrapper })
    await waitFor(() => expect(result.current.user?.name).toBe('Old Name'))

    act(() => {
      window.dispatchEvent(
        new CustomEvent('profile-updated', { detail: { id: 1, name: 'New Name', role: 'admin' } }),
      )
    })

    expect(result.current.user?.name).toBe('New Name')
  })
})
