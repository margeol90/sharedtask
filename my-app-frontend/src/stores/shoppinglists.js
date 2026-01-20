import { defineStore } from 'pinia'
import api from '../api/http.js'

export const useShoppingListStore = defineStore('shoppingLists', {
  state: () => ({
    lists: [],
    loading: false,
  }),

  actions: {
    async fetchLists() {
      this.loading = true
      try {
        const res = await api.get('/api/shopping-lists')
        this.lists = res.data
      } finally {
        this.loading = false
      }
    },

    async fetchList(id) {
      this.loading = true
      try {
        const res = await api.get(`/api/shopping-list/${id}`)
        this.lists = res.data
      } finally {
        this.loading = false
      }
    },

    async createList(name) {
      const res = await api.post('/api/shopping-list', { name })
      this.lists.push(res.data)
      return res.data
    },
  },
})
