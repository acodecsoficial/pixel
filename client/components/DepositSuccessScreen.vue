<script setup lang="ts">
import { formatCurrency } from '@/helpers/formatters'
import useModalStore from '@/store/modals'
import useAuthStore from '@/store/auth'
import CheckIcon from '@/assets/icons/check.svg?component'

const props = defineProps<{
  amount: number,
}>()

const modals = useModalStore()
const auth = useAuthStore()
</script>

<template>
  <div id="qr-code-screen">
    <h2 class="text-2xl font-bold text-primary">{{ $t('front.deposit-verb') }}</h2>

    <div class="text-center pt-5">
      <CheckIcon class="size-[6.5rem] mx-auto my-8 text-emerald-500" />
      <h2 class="text-2xl font-semibold text-primary mb-4">{{ $t('front.deposit.confirmation', { value: formatCurrency(props.amount) }) }}</h2>
      <p class="text-lg text-gray-400">{{ $t('front.deposit.confirmation-subheading') }}.</p>
      <p class="text-lg text-gray-400">
        {{ $t('front.deposit.new-balance', { value: (auth.isWalletLoading ? '...' : formatCurrency(auth.wallet.balance)) }) }}
      </p>

      <div class="mt-14 mb-3 mx-auto w-fit" @click="modals.showDepositModal = false">
        <RouterLink to="/user/wallet" class="text-primary">{{ $t('front.deposit.view-wallet') }}</RouterLink>
        <Button class="mt-4 mx-auto opacity-70">{{ $t('front.close') }}</Button>
      </div>
    </div>
  </div>
</template>
