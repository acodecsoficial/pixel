<script setup lang="ts">
import { formatCurrency } from '@/helpers/formatters'
import useGlobalStore from '@/store/global';
import useModalStore from '@/store/modals';
import Modal from '@/components/Modal.vue'
import useAuthStore from '@/store/auth'

const global = useGlobalStore()
const modals = useModalStore()
const auth = useAuthStore()

function close() {
  modals.showWithdrawRulesModal = false;
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="420"
  >
    <h2 class="text-2xl font-semibold flex items-center gap-3">
      <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 3H18C20.2 3 22 4.8 22 7C22 8.7 21 10.1 19.5 10.7V7C19.5 6.6 19.3 6.2 19.1 5.9C18.9 5.6 18.4 5.5 18 5.5H6C5.6 5.5 5.2 5.7 4.9 5.9C4.6 6.1 4.5 6.6 4.5 7V10.7C3 10.1 2 8.7 2 7C2 4.8 3.8 3 6 3Z" fill="#9CCE13" fill-opacity="0.3"></path><path clip-rule="evenodd" d="M18 7V19C18 20.1 17.1 21 16 21H8C6.9 21 6 20.1 6 19V7H18ZM10.5 14.5L11.2 15.2V11C11.2 10.6 11.5 10.2 12 10.2C12.5 10.2 12.8 10.5 12.8 11V15.2L13.5 14.5C13.8 14.2 14.3 14.2 14.6 14.5C14.9 14.8 14.9 15.3 14.6 15.6L13.3 16.9C12.6 17.6 11.5 17.6 10.8 16.9L9.5 15.6C9.2 15.3 9.2 14.8 9.5 14.5C9.8 14.2 10.2 14.2 10.5 14.5Z" fill="#9CCE13" fill-rule="evenodd"></path></svg>
      Regras de Saque
    </h2>

    <div class="card bg-white/5 rounded-lg py-5 px-6 mt-6">
      <article class="rules-rows">
        <p>Saque Mínimo: <span>{{ formatCurrency(global.withdraw.min_amount) }}</span></p>
        <!-- <p>Saque Máximo: <span>$&nbsp;1.000,00/Saque</span></p> -->
        <p>Quantidade de Saques: <span>{{ global.withdraw.daily_limit >= 50 ? 'Não há limite' : `${global.withdraw.daily_limit}/Dia` }}</span></p>
        <p v-if="auth.wallet.rollover > 0">Apostas para Habilitar o Saque: <span>{{ auth.wallet.rollover }}x</span></p>
        <p>Valor Máximo de Saque de Bônus: <span>3x</span></p>
      </article>

      <hr class="mt-6">

      <article class="rules-text">
        <!-- <p>O usuário tem a opção de realizar 1 saques diariamente, desde que respeite um limite máximo de saque diário de <strong>$&nbsp;1.000,00</strong>.</p> -->
        <p>Os saques via PIX são processados de forma imediata e encaminhados para a conta bancária associada à chave PIX do usuário.</p>
        <p>Adicionalmente, para efetuar o saque, o usuário deve apostar o valor equivalente a <strong>{{ auth.wallet.rollover }}x</strong> o montante do seu depósito.</p>
        <p>É importante ressaltar que esta regra não suspende as regras de rollover do bônus, para realizar o saque do bônus o usuário deverá cumprir o regulamento.</p>
        <p>Esta regra é vigente apenas para o saque de <strong>SALDO REAL</strong>. A regra vigente para o saque de bônus é o rollover.</p>
      </article>
    </div>
  </Modal>
</template>

<style scoped>
.rules-rows {
  @apply flex flex-col gap-3;
}

.rules-rows p {
  @apply font-medium;
}

.rules-rows span {
  @apply bg-primary/10 text-primary text-sm font-medium rounded-md p-1.5 py-0.5;
}

.rules-text {
  @apply text-sm pt-2.5;
}

.rules-text p {
  @apply my-2;
}
</style>
