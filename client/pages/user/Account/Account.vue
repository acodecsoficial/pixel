<script setup>
import { computed } from 'vue'
import { defaultAvatar } from '@/helpers/constants'
import useAuthStore from '@/store/auth'
import useModalStore from '@/store/modals'
import RequiresLogin from '@/components/RequiresLogin.vue'
import LevelCard from '@/components/LevelCard.vue'
import CircularProgress from '@/components/CircularProgress.vue'
import ProfileIcon from '@/assets/icons/profile.svg?component'
import PencilIcon from '@/assets/icons/pencil.svg?component'
import ChevronIcon from '@/assets/icons/chevron-right.svg?component'
import DocumentIcon from '@/assets/icons/document.svg?component'
import EmailIcon from '@/assets/icons/email.svg?component'
import KeyIcon from '@/assets/icons/key.svg?component'
import WalletIcon from '@/assets/icons/wallet.svg?component'
import AffiliateIcon from '@/assets/icons/painel-afiliado.svg?component'
import LogoutIcon from '@/assets/icons/logout.svg?component'
import { trans } from 'laravel-vue-i18n'

const auth = useAuthStore()
const modals = useModalStore()

const links = computed(() => [
  { text: trans('front.account.personal-infos'), icon: DocumentIcon, href: '/user/account/personal-infos', sub: 'Nome, username e CPF' },
  { text: trans('front.account.contact-infos'), icon: EmailIcon, href: '/user/account/contact-infos', sub: 'E-mail e telefone' },
  { text: trans('front.account.change-pass'), icon: KeyIcon, href: '/user/account/change-password', sub: 'Ou redefinir' },
  { text: trans('front.transactions'), icon: WalletIcon, href: '/user/wallet', sub: 'Histórico de depósitos e saques' },
  { text: trans('front.affiliate-panel'), icon: AffiliateIcon, href: '/user/refers', sub: 'Acessar painel' },
])
</script>

<template>
  <RequiresLogin>
    <div class="max-w-[500px] mx-auto">

      <div class="text-center space-y-3 mt-5 mb-8">
        <div class="relative w-fit mx-auto cursor-pointer select-none group" @click="modals.showPickAvatarModal = true">
          <img :src="auth.user?.avatar ?? defaultAvatar" class="rounded-full size-[5.7rem] mx-auto" />
          <PencilIcon class="bg-surface border border-white/5 rounded-full size-7 p-1.5 absolute z-[2] bottom-0 right-0 transition-transform group-active:scale-90" />

          <!-- <CircularProgress :progress="40" /> -->
        </div>
        <div class="opacity-75">{{ auth.user?.email }}</div>
        <h2 class="text-2xl md:text-3xl font-semibold">{{ $t('front.account.configs') }}</h2>
      </div>

      <!-- <LevelCard /> -->

      <div class="bg-surface rounded-custom-max my-8 overflow-hidden">
        <RouterLink
          v-for="link in links"
          :to="link.href"
          class="pl-5 flex items-center gap-3 !no-underline cursor-pointer hover:bg-white/5 group"
        >
          <component :is="link.icon" class="size-[1.35rem] mr-1 opacity-75" />

          <div class="grow leading-3 py-4 pr-5 flex items-center border-b border-white/10 group-last:border-none">
            <div>
              <div class="font-medium md:text-lg">{{ link.text }}</div>
              <span class="text-sm opacity-60">{{ link.sub }}</span>
            </div>

            <ChevronIcon class="text-primary size-5 ml-auto" />
          </div>
        </RouterLink>
      </div>

      <Button class="w-full text-rose-500 text-[1.1rem] py-3">
        <LogoutIcon class="size-5" />
        {{ $t('front.logout') }}
      </Button>

    </div>
  </RequiresLogin>
</template>
