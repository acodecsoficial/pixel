import axios from 'axios'
import { getActiveLanguage } from 'laravel-vue-i18n'


const base = (import.meta as any).env.BASE_URL || '/'

export const siteBaseURL = window.location.origin + base.replace(/\/?$/, '')

const api = axios.create({
  baseURL: base + 'api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Adiciona o token jwt em todas as solicitações
api.interceptors.request.use(function (config) {
  if (typeof window !== 'undefined') {
    const jwtToken = localStorage.getItem('token')
    if (jwtToken) {
      config.headers.Authorization = `Bearer ${jwtToken}`;
    }

    const lang = getActiveLanguage()
    if (lang) {
      config.headers['Accept-Language'] = lang;
    }
  }
  return config;
});

export default api
