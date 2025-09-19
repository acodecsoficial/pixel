<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import useModalStore from '@/store/modals';
import api from '@/services/api';
import useAuthStore from '@/store/auth'
import useGlobalStore from '@/store/global'
import Modal from '@/components/Modal.vue'
import Input from '@/components/Input.vue'
import Button from '@/components/Button.vue'
import EyeIcon from '@/assets/icons/eye.svg?component'
import EyeClosedIcon from '@/assets/icons/eye-closed.svg?component'
import { trans } from 'laravel-vue-i18n'

const global = useGlobalStore();
const modals = useModalStore();
const auth = useAuthStore();

const data = reactive({
  email: '',
  password: '',
  captchaToken: '',
})
const showPassword = ref(false)
const error = ref<string | null>(null)
const isLoading = ref(false)

onMounted(() => {
  document.getElementById('email-input')!.focus()

  renderCaptcha()
})

function renderCaptcha() {
  if (global.cloudflare_sitekey) {
    window.turnstile?.render('.cf-turnstile', {
      sitekey: global.cloudflare_sitekey,
      theme: 'dark',
      callback: function(token: string) {
        data.captchaToken = token;
      },
    });
  }
}

async function login (event: Event) {
  if (global.cloudflare_sitekey && !data.captchaToken) {
    return error.value = trans('front.captcha-required');
  }

  error.value = null
  isLoading.value = true

  await api.post('/auth/login', {
    email: data.email,
    password: data.password,
    captcha_token: data.captchaToken,
  })
    .then(res => {
      if (res.data.message === "Token successfully created") {
        // Success
        localStorage.setItem('token', res.data.access_token)
        auth.fetchUser()
        close()
        window.notify(trans('front.logged-in'), 'success')

        if (location.search.includes('deposit')) {
          modals.openDepositModal()
        }
      }
      else {
        window.notify(trans('front.login-failed'), 'error')
        renderCaptcha()
      }
    })
    .catch((e) => {
      const errorMsg = e.response.data?.message
      if (errorMsg.includes('Essas credenciais não correspondem')) {
        error.value = trans('front.credentials-wrong');
      }
      else if (errorMsg === "Captcha was not valid") {
        error.value = trans('front.captcha-required');
      }
      else if (errorMsg === "Account not found") {
        error.value = trans('front.account-not-found');
      }
      else {
        window.notify(trans('front.login-failed'), 'error')
      }

      renderCaptcha()
    })
    .finally(() => {
      isLoading.value = false
    });
}

function close() {
  modals.showLoginModal = false;
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="420"
    modal-class="auth-modal auth-modal--login"
  >
    <img :src="global.logoURL" class="auth-modal__logo w-[200px] block mx-auto mb-10 mt-3" :alt="global.website_name">

    <form class="auth-modal__form" @submit.prevent="login">
      <Input
        :label="$t('front.email')"
        type="email"
        name="email"
        id="email-input"
        wrapper-class="auth-modal__input"
        required
        :modelValue="data.email"
        @update:modelValue="newValue => data.email = newValue"
      />

      <Input
        :label="$t('front.pass')"
        :type="showPassword ? 'text' : 'password'"
        required
        wrapper-class="auth-modal__input"
        :modelValue="data.password"
        @update:modelValue="newValue => data.password = newValue"
      >
        <template v-slot:after_input>
          <div class="cursor-pointer p-0.5" @click="showPassword = !showPassword">
            <EyeClosedIcon v-if="showPassword" class="size-5" />
            <EyeIcon v-else class="size-5 " />
          </div>
        </template>
      </Input>
      <div class="text-right text-sm text-gray-400 -mt-2 mb-5"><a @click.prevent="modals.openForgotPasswordModal()">{{ $t('front.forgot-pass') }}</a></div>

      <div class="cf-turnstile w-fit mx-auto my-6"></div>

      <p class="auth-modal__error bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-3.5 my-3" v-if="error">{{ error }}</p>

      <Button
        type="submit"
        primary
        class="auth-modal__submit-button w-full text-lg font-semibold py-3.5 mt-4"
        :disabled="isLoading"
      >{{ $t('front.login-enter')}}</Button>
    </form>

    <div v-if="global.google_login_enabled" class="text-neutral-400/90 border-t border-current pt-8 mt-10 relative">
      <span class="absolute top-0 left-2/4 -translate-x-2/4 -translate-y-2/4 bg-background px-4 py-1">OU</span>

      <a href="/auth/google/redirect">
        <Button class="flex-1 w-full py-3 text-base text-neutral-300 bg-transparent border border-current">
          <GoogleLogo class="size-5 text-current"/>
          {{ $t('front.login-with', { service: 'Google' }) }}</Button>
      </a>
    </div>

    <p class="pt-10 text-center text-base">
      {{ $t('front.login-register-heading') }}
      <a @click.prevent="modals.openRegisterModal()" class="text-primary block">{{ $t('front.login-register') }}</a>
    </p>
  </Modal>
</template>
