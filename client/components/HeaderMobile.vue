<script setup lang="ts">
import useGlobalStore from '@/store/global'
import useAuthStore from '@/store/auth'
import useModalStore from '@/store/modals'
import Button from '@/components/Button.vue'
import BackIcon from '@/assets/icons/back.svg?component'
import { useRouter } from 'vue-router'

const global = useGlobalStore()
const modals = useModalStore()
const auth = useAuthStore()
const router = useRouter()

function back() {
  const currentUrl = router.currentRoute.value.fullPath
  if (currentUrl.includes('pgsoft')) {
    router.push('/')
  } else {
    router.back()
  }
}
</script>

<template>
  <header class="header-mobile h-[42px] bg-background border-b border-[#fdffff1a] flex justify-between items-center relative px-2">
    <a @click.prevent="back()">
      <BackIcon class="size-7 p-1" />
    </a>

    <RouterLink to="/" class="inline-block">
      <img
        :src="global.logoURL"
        class="header-mobile-logo h-auto max-h-[20px] max-w-[100px] block"
        :alt="global.website_name"
      >
    </RouterLink>

    <Button
      v-if="auth.isAuthenticated"
      primary
      class="header-mobile-deposit uppercase text-[0.83rem] font-medium px-2 py-1 shadow-md shadow-primary/40 hover:scale-105"
      @click="modals.openDepositModal()"
    >
      {{ $t('front.deposit-verb') }}
    </Button>
  </header>
</template>
