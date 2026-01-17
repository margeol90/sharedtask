import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useAuthStore } from '../../src/stores/auth'
import { setActivePinia, createPinia } from 'pinia'
import axios from 'axios'

vi.mock('axios')

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
    localStorage.clear()
  })

  it('logs in a user successfully', async () => {
    const fakeUser = { id: 1, name: 'Test User', email: 'test@example.com' }
    axios.post.mockResolvedValueOnce({ 
      data: { 
        user: fakeUser,
        accounts: [],
        active_account: { id: 1, name: 'Main' },
        token: 'fake-token'
      }
    })
    
    const auth = useAuthStore()
    await auth.login('test@example.com', 'password')
    
    expect(auth.isAuthenticated).toBe(true)
    expect(auth.user.name).toBe('Test User')
  })

  it('fails login with wrong credentials', async () => {
    axios.post.mockRejectedValueOnce(new Error('Invalid credentials'))
    
    const auth = useAuthStore()
    let error = null
    try {
      await auth.login('wrong@example.com', 'wrong')
    } catch (e) {
      error = e
    }
    
    expect(error).not.toBeNull()
    expect(auth.isAuthenticated).toBe(false)
    expect(auth.user).toBeNull()
  })

  it('is not authenticated by default', () => {
    const auth = useAuthStore()
    expect(auth.user).toBe(null)
    expect(auth.isAuthenticated).toBe(false)
  })

  it('init() sets isReady to true when no token exists', async () => {
    const auth = useAuthStore()
    await auth.init()
    expect(auth.isReady).toBe(true)
    expect(auth.user).toBe(null)
  })

  it('init() fetches user when token exists', async () => {
    localStorage.setItem('token', 'test-token')
    axios.get.mockResolvedValueOnce({ 
      data: { 
        user: { id: 1, name: 'Test User' },
        accounts: [],
        active_account: { id: 1, name: 'Main' }
      } 
    })

    const auth = useAuthStore()
    await auth.init()

    expect(axios.get).toHaveBeenCalledWith('http://localhost:8000/api/me')
    expect(auth.user.name).toBe('Test User')
    expect(auth.isAuthenticated).toBe(true)
  })

  it('init() clears token when /me fails', async () => {
    localStorage.setItem('token', 'bad-token')
    axios.get.mockRejectedValueOnce(new Error('Unauthorized'))

    const auth = useAuthStore()
    await auth.init()

    expect(auth.user).toBe(null)
    expect(localStorage.getItem('token')).toBe(null)
    expect(auth.isAuthenticated).toBe(false)
  })

  it('logout clears user and token', async () => {
    const auth = useAuthStore()
    auth.user = { id: 1 }
    auth.token = 'token'

    axios.post.mockResolvedValueOnce({})

    await auth.logout()

    expect(auth.user).toBe(null)
    expect(auth.token).toBe(null)
    expect(auth.isAuthenticated).toBe(false)
  })

})
