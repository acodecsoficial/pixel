import { defineStore } from 'pinia';
import api from '@/services/api'
import { trans } from 'laravel-vue-i18n'

interface AuthState {
  isAuthenticated: boolean,
  isLoading: boolean,
  isWalletLoading: boolean,
  user: any | null,
  wallet: any | null,
}

const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    isAuthenticated: false,
    isLoading: false,
    isWalletLoading: false,
    user: null,
    wallet: null,
  }),

  actions: {
    init() {
      const jwt = localStorage.getItem('token')
      if (jwt) {
        this.isLoading = true
        this.fetchUser();
      }
    },

    fetchUser() {
      this.fetchWallet()

      // Dica: O token jwt é automaticamente enviado em todas as requisições (Ver em: services/api.ts)
      return api.get('/user')
        .then(res => {
          this.user = res.data
          this.isAuthenticated = true
        })
        .finally(() => {
          this.isLoading = false
        })
    },

    fetchWallet() {
      this.isWalletLoading = true

      return api.get('/user/wallet')
        .then(res => {
          this.wallet = res.data
          // console.log('res.data', res.data)
        })
        .catch(err => {
          //
        })
        .finally(() => {
          this.isWalletLoading = false
        })
    },

    async register(data: Record<any, any>) {
      const res = await api.post('/auth/register', data)
        .then(res => {
          return res.data
        })

      localStorage.setItem('token', res.access_token)
      this.fetchUser()
      if ('fbq' in window) fbq('track', 'CompleteRegistration');

      return res
    },

    logout() {
      api.post('/auth/logout')

      localStorage.removeItem('token')
      this.$reset()
      window.notify(trans('front.logged-out'))
      setTimeout(() => {
        window.location.reload()
      }, 1000)
    }
  }
})

export default useAuthStore;
