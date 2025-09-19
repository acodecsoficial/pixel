<script setup lang="ts">
import { ref, reactive, watch, computed } from 'vue'
import useModalStore from '@/store/modals';
import { cpfMask, currencyMask, formatCurrency } from '@/helpers/formatters'
import { useRouter } from 'vue-router'
import useAuthStore from '@/store/auth'
import useGlobalStore from '@/store/global'
import api from '@/services/api'
import Modal from '@/components/Modal.vue'
import Button from '@/components/Button.vue'
import Input from '@/components/Input.vue'
import DocumentIcon from '@/assets/icons/document.svg?component'
import LockIcon from '@/assets/icons/lock.svg?component'

const global = useGlobalStore()
const modals = useModalStore()
const auth = useAuthStore()
const router = useRouter()

const showConfirmationScreen = ref(false)
const amount = ref(modals.withdrawInitAmount ?? 0)
const amountAvailable = computed(() =>
  modals.withdrawMode === 'normal'
    ? auth.wallet?.balance
    : modals.withdrawInitAmount
)
const isLoading = ref(false)
const error = ref<string | null>(null)
const disableSubmit = computed(() => isLoading.value || !auth.user.cpf || amount.value <= 0)


function handleFormSubmit() {
  if (amount.value < global.withdraw.min_amount) {
    return error.value = `Valor mínimo do saque é de ${formatCurrency(global.withdraw.min_amount)}`
  }

  error.value = null

  if (
    modals.withdrawMode === 'normal' &&
    auth.wallet?.bonus > 0
  ) {
    showConfirmationScreen.value = true
  } else {
    requestWithdraw()
  }
}

async function requestWithdraw() {
  showConfirmationScreen.value = false
  isLoading.value = true

  const url = modals.withdrawMode === 'affiliate'
    ? '/wallet/request-rewards'
    : '/wallet/withdraw'

  try {
    const response = await api.post(url, {
      amount: amount.value * 100,
    });

    if (!response.data.message.includes('Saque solicitado com sucesso')) {
      return window.notify(response.data.message, 'error');
    }

    // Requested!
    window.notify('Saque solicitado com sucesso', 'success');
    close();

    if ('fbq' in window) fbq('track', 'Withdrawal', { value: amount.value });

    setTimeout(() => {
      window.location.reload();
    }, 1500);
  }
  catch (err: any) {
    error.value = err.response?.data?.message ?? 'Ocorreu um problema ao solicitar seu saque'

    window.notify('Ocorreu um problema ao solicitar seu saque', 'error');
  }
  finally {
    isLoading.value = false;
  }
}

function close() {
  modals.showWithdrawModal = false;
}
function goToAccountPage() {
  close()
  router.push('/user/account/personal-infos')
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="520"
  >

    <div id="confirm-screen" v-if="showConfirmationScreen">
      <h2 class="text-primary text-3xl text-center mb-10" v-html="$t('front.confirm-withdrawal')"></h2>

      <div class="flex gap-5 *:flex-[1_1_auto]">
        <Button
          primary
          class="text-xl py-3"
          @click="requestWithdraw"
          :disabled="isLoading"
        >{{ $t('front.confirm') }}</Button>
        <Button
          class="bg-rose-500/15 text-rose-500 text-xl py-3"
          @click="showConfirmationScreen = false"
          :disabled="isLoading"
        >{{ $t('front.cancel') }}</Button>
      </div>
    </div>

    <div id="form-screen" v-else>
      <h2 class="text-2xl font-bold text-primary mb-4">{{ $t('front.request-withdrawal') }}</h2>

      <p class="text-amber-500 text-sm" v-html="$t('front.withdrawal-instructions')"></p>

      <Button
        v-if="modals.withdrawMode === 'normal'"
        class="w-full bg-yellow-300/15 text-yellow-300 text-sm py-3 my-3 mb-6"
        @click="modals.showWithdrawRulesModal = true"
      >{{ $t('front.withdraw-questions') }}</Button>

      <form @submit.prevent="handleFormSubmit">

        <div class="my-4">
          <label for="amount-input" class="block font-medium text-sm mb-1 required">{{ $t('front.withdrawal-amount') }}:</label>
          <Input
            type="number"
            inputmode="numeric"
            name="amount"
            id="amount-input"
            required
            :modelValue="currencyMask(amount)"
            @update:modelValue="(newValue = '0,00') => amount = Number(newValue.replace(/[\.\,]/gi, ''))"
            class="[&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none "
          >
            <template #before_input>
              <strong class="font-semibold">$</strong>
            </template>
          </Input>

          <div class="-mt-1.5 text-sm">
            {{ $t('front.withdrawal-available') }}:
            <span class="text-primary font-semibold">{{ formatCurrency(amountAvailable) }}</span>
          </div>
          <div class="text-sm" v-if="modals.withdrawMode === 'normal' && global.withdraw.tax_active">
            {{ $t('front.withdrawal-tax') }}:
            <span class="text-primary font-semibold">{{ global.withdraw.tax_value }}%</span>
          </div>
        </div>

        <p v-if="!auth.user.cpf" class="bg-rose-600/10 rounded-lg text-rose-500 text-left leading-5 p-3.5 my-4 flex items-center gap-2.5">
          {{ $t('front.withdrawal-requires-cpf') }}
          <Button class="text-nowrap shrink-0" @click="goToAccountPage">{{ $t('front.withdrawal-cpf') }}</Button>
        </p>
        <div v-else class="my-4">
          <label for="document-input" class="block font-medium text-sm mb-1">{{ $t('front.pix-key') }}:</label>
          <Input
            type="text"
            name="document"
            id="document-input"
            required
            maxlength="14"
            :disabled="true"
            :modelValue="cpfMask(auth.user.cpf)"
            @update:modelValue="newValue => {}"
          >
            <template #before_input>
              <DocumentIcon class="size-5 opacity-60" />
            </template>

            <template #after_input>
              <LockIcon class="size-4 opacity-80" />
            </template>
          </Input>
          <div class="text-base text-gray-400 -mt-1.5">{{ $t('front.pix-input-helper') }}.</div>
        </div>

        <p class="bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-3.5 my-2" v-if="error">{{ error }}</p>

        <Button
          primary
          class="w-full py-3 mt-6 text-xl font-semibold shadow-md shadow-primary/40"
          :disabled="disableSubmit"
        >
          {{ $t('front.withdrawal-verb') }} {{ amount > 0 ? formatCurrency(amount) : '' }}
        </Button>
      </form>
    </div>

  </Modal>
</template>

<style scoped>
.required::after {
  @apply text-[#ff6969] text-sm content-['*'] ml-1;
}
</style>
