<script setup lang="ts">
import { ref, reactive, onUnmounted } from 'vue'
import useModalStore from '@/store/modals';
import useAuthStore from '@/store/auth'
import api from '@/services/api'
import Modal from '@/components/Modal.vue'
import DepositForm from '@/components/DepositForm.vue'
import DepositQRCodeScreen from '@/components/DepositQRCodeScreen.vue'
import DepositSuccessScreen from '@/components/DepositSuccessScreen.vue'

const modals = useModalStore()
const auth = useAuthStore()

const data = reactive({
  amount: 0,
  document: auth.user.cpf ? auth.user.cpf : '',
  accept_bonus: false,
  coupon: '',

  pixCode: '',
  qrCode: '',
  depositId: 0,
})
const step = ref<'form'|'qrcode'|'success'>('form')
const isLoading = ref(false)
let checkInterval: any;

async function deposit() {
  isLoading.value = true
  step.value = 'qrcode'

  if ('fbq' in window) fbq('track', 'Deposit');

  await api.post('/wallet/add-credit', {
    credit_amount: data.amount * 100,
    document: data.document,
    coupon_code: data.coupon,
    accept_bonus: data.accept_bonus,
  })
    .then(res => {
      data.pixCode = res.data.br_code
      data.qrCode = res.data.qr_code
      data.depositId = res.data.deposit_id

      checkInterval = setInterval(checkPaymentStatus, 3000)
    })
    .catch(err => {
      step.value = 'form'

      const msg = err.response?.data?.message ?? 'Ocorreu um problema ao gerar a chave PIX'
      window.notify(msg, 'error')
    })
    .finally(() => {
      isLoading.value = false
    })
}

// Função que verifica periodicamente se o depósito foi marcado como pago no DB
async function checkPaymentStatus() {
  try {
    const response = await api.get(`/wallet/deposits/${data.depositId}/status`)

    if (response.data === 'paid') {
      deposited()
      clearInterval(checkInterval)
    }
  }
  catch (err) {
    clearInterval(checkInterval)
  }
}

function deposited() {
  step.value = 'success'
  auth.fetchWallet()

  if ('fbq' in window) fbq('track', 'DepositConfirmation', { value: data.amount });
}

onUnmounted(() => {
  clearInterval(checkInterval)
})

function close() {
  modals.showDepositModal = false;
}
function closeAndReload() {
  close()
  window.location.reload()
}
</script>

<template>
  <Modal
    :on-close="close"
    :close-on-backdrop-click="step === 'form'"
    :modal-width="520"
  >

    <KeepAlive>
      <DepositForm
        v-if="step === 'form'"
        :data="data"
        @submit="deposit"
      />
    </KeepAlive>

    <DepositQRCodeScreen
      v-if="step === 'qrcode'"
      :data="data"
      @check-payment-status="checkPaymentStatus"
      @close="closeAndReload"
    />

    <DepositSuccessScreen
      v-if="step === 'success'"
      :amount="data.amount"
    />

  </Modal>
</template>

<script lang="ts">
export interface DepositData {
  pixCode: string,
  qrCode: string,
  depositId: number,
  amount: number,
  document: string,
  accept_bonus: boolean,
  coupon: string,
}
</script>
