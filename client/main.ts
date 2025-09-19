import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createRouter, createWebHistory } from 'vue-router'
import { i18nVue } from 'laravel-vue-i18n'
import PrimeVue from 'primevue/config'
import App from './App.vue'
import HomePage from '@/pages/Home.vue'
import GamePage from '@/pages/casino/Game.vue'
import AllGamesPage from '@/pages/casino/AllGames.vue'
import CategoryPage from '@/pages/casino/Category.vue'
// @ts-ignore
import SportsPage from '@/pages/Sports.vue'
import AccountPage from '@/pages/user/Account/Account.vue'
import ChangePassPage from '@/pages/user/Account/ChangePassword.vue'
import PersonalInfos from '@/pages/user/Account/PersonalInfos.vue'
import ContactInfos from '@/pages/user/Account/ContactInfos.vue'
import RefersPage from '@/pages/user/Refers.vue'
import WalletPage from '@/pages/user/Wallet.vue'
import TermsPage from '@/pages/Terms.vue'
import PrivacyPage from '@/pages/privacy.vue'
import AmlPolicyPage from '@/pages/aml-policy.vue'
import ResponsibleGamingPage from '@/pages/responsible-gaming.vue'
import BettingTermsPage from '@/pages/betting-terms.vue'
// @ts-ignore
import NotFound from '@/pages/404.vue'

const pinia = createPinia()

const routes = [
  { path: '/', component: HomePage },
  { path: '/casino/:provider', component: CategoryPage, name: 'provider' },
  { path: '/casino/:provider/:game', component: GamePage, name: 'game' },
  { path: '/casino/category/all', component: AllGamesPage },
  { path: '/sports', component: SportsPage },
  { path: '/user/account', component: AccountPage, name: 'account' },
  { path: '/user/account/personal-infos', component: PersonalInfos },
  { path: '/user/account/contact-infos', component: ContactInfos },
  { path: '/user/account/change-password', component: ChangePassPage },
  { path: '/user/refers', component: RefersPage },
  { path: '/user/wallet', component: WalletPage },
  { path: '/terms', component: TermsPage },
  { path: '/privacy', component: PrivacyPage },
  { path: '/aml-policy', component: AmlPolicyPage },
  { path: '/betting-terms', component: BettingTermsPage },
  { path: '/responsible-gaming', component: ResponsibleGamingPage },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    const scrollPosition = savedPosition ? savedPosition : { top: 0 }
    document.querySelector('main')?.scrollTo(scrollPosition)
  },
})

createApp(App)
  .use(router)
  .use(pinia)
  .use(PrimeVue)
  .use(i18nVue, {
    lang: localStorage.getItem('lang') ?? 'pt_BR',
    resolve: async (lang: any) => {
        const languages = import.meta.glob('../lang/*.json');
        return await languages[`../lang/${lang}.json`]();
    }
  })
  .mount('#app')
