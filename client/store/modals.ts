import { defineStore } from 'pinia';

const useModalStore = defineStore('modals', {
  state: () => ({
    showLoginModal: false,
    showRegisterModal: false,
    showForgotPasswordModal: false,
    showDepositModal: false,
    showWithdrawModal: false,
    showBonusRulesModal: false,
    showWithdrawRulesModal: false,
    showPickAvatarModal: false,

    withdrawMode: 'normal' as 'normal' | 'affiliate',
    withdrawInitAmount: 0,
  }),

  actions: {
    openLoginModal() {
      this.showLoginModal = true;
      this.showRegisterModal = false;
      this.showForgotPasswordModal = false;
    },

    openRegisterModal() {
      this.showRegisterModal = true;
      this.showLoginModal = false;
    },

    openForgotPasswordModal() {
      this.showForgotPasswordModal = true;
      this.showLoginModal = false;
      this.showRegisterModal = false;
    },

    openDepositModal() {
      this.showDepositModal = true;
    },

    openWithdrawModal(mode: 'normal' | 'affiliate', initialAmount?: number) {
      this.withdrawMode = mode
      this.withdrawInitAmount = initialAmount ?? 0
      this.showWithdrawModal = true;
    },

    init() {
      const isAuthenticated = !!localStorage.getItem('token')
      const url = new URL(window.location.href);

      if (url.searchParams.has('ref') && !isAuthenticated) {
        this.openRegisterModal()
      }

      if (url.searchParams.has('deposit')) {
        if (isAuthenticated) {
          this.openDepositModal()
        } else {
          this.openLoginModal()
        }
      }
    }
  }
})

export default useModalStore
