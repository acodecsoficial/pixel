import { defineStore } from 'pinia'
import api, { siteBaseURL } from '@/services/api'
import { convertHexToRGB } from '@/helpers/hexToRGB'
import type { Banner, Theme, Social, DesktopActionBtn, MobileActionBtn, FooterEmails, Colors } from '@/interfaces'
import imgBaseUrl from '@/helpers/imgBaseUrl'

interface GlobalState {
  showSidebar: boolean,
  showTopMessage: boolean,
  showHeaderSearchBox: boolean,
  logo: string
  favicon: string
  icon_img: string
  website_name: string
  banners: Banner[]
  theme: Theme
  social: Social
  created_at: string
  updated_at: string
  desktop_action_btns: DesktopActionBtn[]
  mobile_action_btns: MobileActionBtn[]
  cloudflare_sitekey: '',
  footer_emails: FooterEmails
  sponsorships: any[]
  sidebar_links: any[]
  colors: Colors
  primary_color: string,
  withdraw: {
    tax_active: number,
    tax_value: number,
    min_amount: number,
    max_automatic: number,
    daily_limit: number,
  },
  deposit: {
    min_amount: number,
    show_bonus_banner: boolean
  },
  cpa: number,
  cpa_min: number,
  sports_enabled: boolean,
  google_login_enabled: boolean,
  bonus_percent: number,
}

const useGlobalStore = defineStore('global', {
  // @ts-ignore
  state: () => ({
    showSidebar: true,
    showTopMessage: localStorage.getItem('top-msg-visible') != 'false',
    showHeaderSearchBox: false,

    logo: '',
    favicon: '',
    icon_img: '',
    website_name: '',
    banners: [],
    theme: {},
    social: {},
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
    desktop_action_btns: [],
    mobile_action_btns: [],
    sidebar_links: [],
    cloudflare_sitekey: '',
    footer_emails: {},
    sponsorships: [],
    colors: {},
    primary_color: null,
    withdraw: {
      tax_active: 0,
      tax_value: 0,
      min_amount: 0,
      max_automatic: 0,
      daily_limit: 1
    },
    deposit: {
      min_amount: 0,
      show_bonus_banner: false,
    },
    cpa: 0,
    cpa_min: 0,
    sports_enabled: false,
    google_login_enabled: false,
    bonus_percent: 0,
  } as GlobalState),

  getters: {
    logoURL: (state) => imgBaseUrl(state.logo),
    // logoURL: (state) => `/flashgames.webp`,
  },

  actions: {
    async init() {
      const data = JSON.parse(document.querySelector('script#configs')!.innerHTML)

      // Prepare state
      for (const key in data.social) {
        const value = data.social[key]
        if (!value?.includes('http')) data.social[key] = `https://${value}`
      }

      // Set state
      this.$patch(data)
    },

    toggleSidebar() {
      this.showSidebar = !this.showSidebar
    },

    toggleHeaderSearchBox() {
      this.showHeaderSearchBox = !this.showHeaderSearchBox
    },

    hideTopMessage() {
      this.showTopMessage = false
      localStorage.setItem('top-msg-visible', 'false')
    },
  },
})

export default useGlobalStore
