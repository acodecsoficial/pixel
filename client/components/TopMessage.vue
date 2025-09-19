<script setup>
import { computed } from 'vue'
import { formatCurrency } from '@/helpers/formatters'
import useGlobalStore from '@/store/global'
import Button from '@/components/Button.vue'
import CloseIcon from '@/assets/icons/close.svg?component'
import { trans } from 'laravel-vue-i18n'

const global = useGlobalStore()

const message = computed(() => trans('front.refer-message', { value: formatCurrency(global.cpa) }) )
</script>

<template>
  <div class="refers-banner bg-primary text-primary-contrast flex justify-between items-center gap-3 relative group">
    <span />

    <RouterLink to="/user/refers" class="flex items-center justify-center px-4 py-2 cursor-pointer">
      <span class="text-center text-sm sm:text-base forn-semibold leading-4 pr-6">{{ message }}</span>
      <Button class="bg-primary-contrast text-primary text-sm px-2 py-1 shrink-0">{{ $t('front.redeem') }}</Button>
    </RouterLink>

    <CloseIcon
      @click="global.hideTopMessage()"
      class="size-7 text-current p-1 mr-1.5 justify-self-end cursor-pointer"
    />
    <span class="invisible bg-black/5 absolute inset-0 pointer-events-none group-hover:visible" />
  </div>
</template>
