<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import useGlobalStore from '@/store/global'
import useModalStore from '@/store/modals'
import useAuthStore from '@/store/auth'
import MenuIcon from '@/assets/icons/hamburguer-menu.svg?component'
import HomeIcon from '@/assets/icons/home.svg?component'
import DepositIcon from '@/assets/icons/wallet-deposit.svg?component'
import personIcon from '@/assets/icons/person.svg?component'
import WalletIcon from '@/assets/icons/wallet.svg?component'
import SportsIcon from '@/assets/icons/soccer-ball.svg?component'
import LockIcon from '@/assets/icons/lock.svg?component'
import VideoIcon from '@/assets/icons/video.svg?component'
import DeckIcon from '@/assets/icons/deck.svg?component'
import BoxingIcon from '@/assets/icons/boxing.svg?component'
import DicesIcon from '@/assets/icons/dices.svg?component'

const route = useRoute()
const global = useGlobalStore()
const auth = useAuthStore()
const modals = useModalStore()

const showAoVivo = ref(false)

onMounted(function () {
  setInterval(() => showAoVivo.value = !showAoVivo.value, 2000)
})

const handleSportLinkClick = (event) => {
  if (!global.sports_enabled) {
    event.preventDefaut()
    return window.notify('Área de esportes bloqueada', 'info')
  }
}

const isEsporteExclusivo = window.location.host === 'esportexclusivo.bet'
const showMMA = window.location.host.replace('www.', '') === 'feier-ex.bet'
</script>

<template>
  <div
    class="bg-surface border-t border-white/10 z-[160] h-[60px] flex-[0_0_auto] shadow-[0px_-3px_8px_rgba(0_0_0_/_15%)] md:hidden"
    @click="global.showSidebar = false"
  >
    <div class="flex gap-0 h-full max-w-[500px] px-1 mx-auto *:flex-1">

      <button
        class="button"
        :class="{ 'selected': global.showSidebar }"
        @click="global.toggleSidebar(); $event.stopPropagation()"
      >
        <MenuIcon class="size-5" />
        <span>{{ $t('front.menu') }}</span>
      </button>

      <RouterLink
        to="/"
        class="button"
        :class="{ 'selected': route.path === '/' }"
      >
        <HomeIcon class="size-6" />
        <span>{{ $t('front.home') }}</span>
      </RouterLink>

      <template v-if="auth.isAuthenticated">
        <button
          class="button"
          :class="{ 'selected': modals.showDepositModal }"
          @click="modals.openDepositModal()"
        >
          <div class="size-6 relative">
            <DepositIcon class="bg-primary text-primary-contrast border-[5px] border-[#323637] rounded-full p-2.5 size-[3.68rem] absolute -bottom-0.5 left-2/4 -translate-x-2/4" />
          </div>
          <span class="text-sm">{{ $t('front.deposit-verb') }}</span>
        </button>

        <RouterLink
          :to="isEsporteExclusivo ? '/sports' : '/user/wallet'"
          class="button"
          :class="{
            'selected': route.path.includes(isEsporteExclusivo ? '/sports' : 'user/wallet')
          }"
        >
          <component :is="isEsporteExclusivo ? SportsIcon : WalletIcon" class="size-6" />
          <span>{{ isEsporteExclusivo ? $t('front.sports') : $t('front.wallet') }}</span>
        </RouterLink>

        <RouterLink
          to="/user/account"
          class="button"
          :class="{ 'selected': route.path.includes('user/account') }"
        >
          <personIcon class="size-6" />
          <span>{{ $t('front.account-verb') }}</span>
        </RouterLink>
      </template>

      <template v-else>
        <RouterLink
          :to="global.sports_enabled ? '/sports' : '#'"
          class="button"
          :class="{
            'cursor-default pointer-events-none': !global.sports_enabled,
            'selected': route.path.includes('sports')
          }"
          @click="handleSportLinkClick"
        >
          <component
            :is="global.sports_enabled ? SportsIcon : LockIcon"
            class="size-5"
          />
          <span>{{ $t('front.sports') }}</span>
        </RouterLink>

        <a
          v-if="showMMA"
          href="https://feier-exfight.club/"
          class="button"
        >
          <BoxingIcon class="size-6" />
          <span>MMA</span>
        </a>

        <RouterLink
          v-else
          to="/casino/Evolution"
          class="button live"
          :class="{
            'selected': route.path.includes('casino/Evolution'),
          }"
          @click="handleSportLinkClick"
        >
          <DicesIcon class="size-6" />
          <span>{{ !showAoVivo ? $t('front.bets') : $t('front.live') }}</span>
        </RouterLink>

        <RouterLink
          to="/casino/category/all"
          class="button"
          :class="{
            'selected': route.path.includes('category/all'),
          }"
        >
            <DeckIcon class="size-6" />
            <span>{{ $t('front.casino') }}</span>
        </RouterLink>
      </template>
    </div>
  </div>
</template>

<style scoped>
  .button {
    @apply flex flex-col gap-2 items-center justify-center px-2 pt-1 h-full text-xs font-medium relative text-neutral-300/85 !no-underline active:scale-90 transition-transform;
  }

  .button.selected {
    @apply !text-white;
  }

  .button.live::after {
    @apply bg-rose-600 rounded-full size-1.5 absolute top-2 right-5 animate-pulse;
    content: '';
  }
</style>
