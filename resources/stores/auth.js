import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
    }),

    actions: {
        async checkAuth() {
            try {
                const { data } = await axios.get('/api/user', { withCredentials: true })
                this.user = data
            } catch {
                this.user = null
            }
        },

        async logout() {
            try {
                await axios.post('/api/logout', {}, { withCredentials: true })
            } catch {
                // ignore
            } finally {
                this.user = null
            }
        },
    },
})