import { describe, expect, it, vi, beforeEach } from 'vitest'
import { renderHook, waitFor, act } from '@testing-library/react'
import { useApi, errorMessage } from '@/components/ui'

// Mock the axios client
vi.mock('@/api/client', () => ({
  default: {
    get: vi.fn(),
  },
}))

import api from '@/api/client'

describe('useApi', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })

  it('fetches data on mount and exposes it', async () => {
    api.get.mockResolvedValue({ data: { data: [{ pat_fname: 'Dara' }] } })

    const { result } = renderHook(() => useApi('/patients'))

    // starts loading
    expect(result.current.loading).toBe(true)

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(api.get).toHaveBeenCalledWith('/patients')
    expect(result.current.data).toEqual({ data: [{ pat_fname: 'Dara' }] })
    expect(result.current.error).toBeNull()
  })

  it('exposes the API error message when the request fails', async () => {
    api.get.mockRejectedValue({ response: { data: { message: 'Could not load patients.' } } })

    const { result } = renderHook(() => useApi('/patients'))

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(result.current.error).toBe('Could not load patients.')
  })

  it('uses a fallback message for failures without a response body', async () => {
    api.get.mockRejectedValue(new Error('network down'))

    const { result } = renderHook(() => useApi('/patients'))

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(result.current.error).toBe('Could not load data.')
  })

  it('refetches when the URL changes (search/filter/page)', async () => {
    api.get.mockResolvedValue({ data: { data: [] } })

    const { result, rerender } = renderHook(({ url }) => useApi(url), {
      initialProps: { url: '/patients?page=1' },
    })

    await waitFor(() => expect(result.current.loading).toBe(false))

    rerender({ url: '/patients?page=2' })

    await waitFor(() => expect(api.get).toHaveBeenCalledWith('/patients?page=2'))
    expect(api.get).toHaveBeenCalledTimes(2)
  })

  it('reload() fetches again and sets loading', async () => {
    api.get.mockResolvedValue({ data: { data: [] } })

    const { result } = renderHook(() => useApi('/patients'))
    await waitFor(() => expect(result.current.loading).toBe(false))

    act(() => {
      result.current.reload()
    })
    expect(result.current.loading).toBe(true)

    await waitFor(() => expect(result.current.loading).toBe(false))
    expect(api.get).toHaveBeenCalledTimes(2)
  })
})

describe('errorMessage', () => {
  it('extracts the API message from an axios error', () => {
    const err = { response: { data: { message: 'Invalid email or password.' } } }
    expect(errorMessage(err)).toBe('Invalid email or password.')
  })

  it('falls back when the API gives no message', () => {
    expect(errorMessage({})).toBe('Something went wrong. Please try again.')
    expect(errorMessage(undefined)).toBe('Something went wrong. Please try again.')
  })

  it('accepts a custom fallback', () => {
    expect(errorMessage({}, 'Custom fallback.')).toBe('Custom fallback.')
  })
})
