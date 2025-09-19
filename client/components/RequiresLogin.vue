<script setup lang="ts">
import useAuthStore from '@/store/auth'
import useModalStore from '@/store/modals'
import LoginIcon from '@/assets/icons/login.svg?component'
// @ts-ignore
import authImg from '@/assets/images/auth.png?url'
import Button from '@/components/Button.vue'

const auth = useAuthStore()
const modals = useModalStore()
</script>

<template>
  <template v-if="!auth.isLoading">
    <div v-if="!auth.isAuthenticated" class="require-auth-page w-full py-20 flex flex-col items-center">
      <img :src="authImg" class="mx-auto mb-3 h-[130px]" />
      <div class="text-2xl font-normal text-center mb-8">{{ $t('front.requires-auth') }}</div>
      <Button primary class="" @click="modals.openLoginModal()">
        <LoginIcon class="size-5 text-current" />
        {{ $t('front.login') }}
      </Button>
      <RouterLink to="/" class="text-primary mt-5">{{ $t('front.go-home') }}</RouterLink>
    </div>

    <slot v-else :key="auth.isAuthenticated" />
  </template>
</template>
