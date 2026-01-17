// src/stores/auth.js
import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user')) ?? null,
    accounts: JSON.parse(localStorage.getItem('accounts')) ?? [],
    activeAccount: JSON.parse(localStorage.getItem('activeAccount')) ?? null,
    token: localStorage.getItem('token') ?? null,
    isReady: false,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
  },

  actions: {
    
    saveToken(token) {
      this.token = token
      localStorage.setItem('token', token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    },

    persistAuthState() {
      localStorage.setItem('user', JSON.stringify(this.user))
      localStorage.setItem('accounts', JSON.stringify(this.accounts))
      localStorage.setItem('activeAccount', JSON.stringify(this.activeAccount))
    },

    saveAuthState(data) {
      this.user = {
        id: data.user.id,
        name: data.user.name,
        email: data.user.email
      }
      this.accounts = data.user.accounts
      this.activeAccount = data.user.active_account

      this.saveToken(data.token)
      this.persistAuthState()
    },

    async register(name, email, password, password_confirmation) {
      const response = await axios.post('http://localhost:8000/api/register', {
        name, email, password, password_confirmation
      })
      this.saveAuthState(response.data)
    },

    async login(email, password) {
      const response = await axios.post('http://localhost:8000/api/login', { email, password })
      this.saveAuthState(response.data)
    },

    async fetchMe() {
      const response = await axios.get('http://localhost:8000/api/me')

      this.user = response.data.user
      this.accounts = response.data.user.accounts
      this.activeAccount = response.data.user.active_account

      // update localStorage in case something changed
      localStorage.setItem('user', JSON.stringify(user))
      localStorage.setItem('accounts', JSON.stringify(accounts))
      localStorage.setItem('activeAccount', JSON.stringify(active_account))
    },

    async logout() {
      try {
        await axios.post('http://localhost:8000/api/logout')
      } catch (e) {
        console.warn('Logout API failed, clearing locally')
      }

      this.user = null
      this.accounts = []
      this.activeAccount = null
      this.token = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      localStorage.removeItem('accounts')
      localStorage.removeItem('activeAccount')
      delete axios.defaults.headers.common['Authorization']
    },

    async init() {
      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      }
      this.isReady = true
    },
  }
})
