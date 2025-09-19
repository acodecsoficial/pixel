<script setup lang="ts">
import { ref } from 'vue'
import useModalStore from '@/store/modals';
import useGlobalStore from '@/store/global'
import useAuthStore from '@/store/auth'
import { siteBaseURL } from '@/services/api'
import z from 'zod'
import axios from 'axios'
import Modal from '@/components/Modal.vue'
import Input from '@/components/Input.vue'
import Button from '@/components/Button.vue'
import { trans } from 'laravel-vue-i18n'

const modals = useModalStore()
const global = useGlobalStore()
const auth = useAuthStore()

const email = ref('')
const isLoading = ref(false)
const error = ref<string | null>(null)

const schema = z.string({ required_error: trans('front.email-required') })
  .email({ message: trans('front.email-invalid') })

async function sendResetEmail() {
  const validation = schema.safeParse(email.value)

  if (!validation.success) {
    return error.value = validation.error.issues[0]?.message;
  }
  error.value = null
  isLoading.value = true

  await axios.post(`${siteBaseURL}/auth/send-password-reset`, {
    email: email.value,
  })
    .then((res) => {
      window.notify(trans('front.forgot-email-sent'), 'success', 5000)

      modals.showForgotPasswordModal = false
      modals.showLoginModal = true
    })
    .catch((e) => {
      if (e.response?.status === 404) {
        error.value = trans('front.account-not-found')
      } else {
        error.value = trans('front.forgot-email-not-sent')
      }
    })
    .finally(() => {
      isLoading.value = false
    })
}

function close() {
  modals.showForgotPasswordModal = false;
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="420"
    modal-class="auth-modal auth-modal--forgot"
  >
    <img :src="global.logoURL" class="auth-modal__logo w-[200px] block mx-auto mb-7 mt-2" :alt="global.website_name">

    <h1 class="text-2xl font-semibold text-center mb-7">{{ $t('front.pass-recovery') }}</h1>

    <form class="auth-modal__form" @submit.prevent="sendResetEmail">
      <Input
        :label="$t('front.email')"
        type="email"
        name="email"
        id="email-input"
        wrapper-class="auth-modal__input"
        autocomplete="email"
        required
        :modelValue="email"
        @update:modelValue="newValue => email = newValue"
      />

      <div class="text-sm text-gray-300 -mt-1 mb-5">{{ $t('front.pass-recovery-intructions') }}</div>

      <p class="auth-modal__error bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-3.5 my-3" v-if="error">{{ error }}</p>

      <Button
        type="submit"
        primary
        class="auth-modal__submit-button w-full text-lg font-semibold py-3.5 mt-4"
        :disabled="isLoading"
      >
        {{ isLoading ? trans('front.sending') : trans('front.send-forgot-email') }}
      </Button>
    </form>

    <p class="mt-8 text-center text-base" v-if="!auth.isAuthenticated">
      <a @click.prevent="modals.openLoginModal()" class="text-primary">{{ $t('front.login') }}</a>
    </p>
  </Modal>
</template>
