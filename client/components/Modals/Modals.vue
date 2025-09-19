<script setup lang="ts">
import { onMounted } from 'vue'
import useModalStore from '@/store/modals'
import LoginModal from './LoginModal.vue'
import RegisterModal from './RegisterModal.vue'
import BonusRulesModal from './BonusRulesModal.vue'
import DepositModal from './DepositModal.vue'
import WithdrawModal from './WithdrawModal.vue'
import WithdrawRulesModal from './WithdrawRulesModal.vue'
import ForgotPasswordModal from './ForgotPasswordModal.vue'
import ChangeAvatarModal from './ChangeAvatarModal.vue'

const modals = useModalStore()

onMounted(() => {
  modals.init()
})
</script>

<template>
  <LoginModal v-if="modals.showLoginModal" />
  <RegisterModal v-if="modals.showRegisterModal" />
  <DepositModal v-if="modals.showDepositModal" />
  <WithdrawModal v-if="modals.showWithdrawModal" />
  <BonusRulesModal v-if="modals.showBonusRulesModal" />
  <WithdrawRulesModal v-if="modals.showWithdrawRulesModal" />
  <ForgotPasswordModal v-if="modals.showForgotPasswordModal" />
  <ChangeAvatarModal v-if="modals.showPickAvatarModal" />
</template>
