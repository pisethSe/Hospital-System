import { afterEach } from 'vitest'
import { cleanup } from '@testing-library/react'

// Clean up the DOM between tests
afterEach(() => {
  cleanup()
  localStorage.clear()
})
