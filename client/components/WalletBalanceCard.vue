<script setup lang="ts">
import { formatCurrency } from '@/helpers/formatters'
import useAuthStore from '@/store/auth'
import reloadIcon from '@/assets/icons/reload.svg?url'
import Button from '@/components/Button.vue'

const props = defineProps<{
  heading: string,
  balance: number
}>()

const auth = useAuthStore()
</script>

<template>
  <div class="balance-card bg-white/10 rounded-custom-max p-6 pb-7 flex flex-col flex-[1_1_auto]">
    <div class="flex justify-between items-center opacity-60">
      <span class="text-lg font-medium">{{ props.heading }}</span>
      <slot name="icon" />
    </div>

    <div class="flex justify-between items-center mt-2">
      <div class="font-semibold text-5xl">
        {{ formatCurrency(props.balance) }}
      </div>
      <Button @click="auth.fetchWallet()" class="bg-transparent p-2 mt-2 opacity-50 hover:opacity-100" title="Atualizar valores" >
        <img :src="reloadIcon" class="icon size-5" :class="{ 'animate-spin': auth.isWalletLoading }">
      </Button>
    </div>

    <slot name="after_balance" />

    <div class="mb-8"/>

    <div class="flex gap-4 mt-auto flex-wrap">
      <slot name="buttons" />
    </div>
  </div>
</template>
