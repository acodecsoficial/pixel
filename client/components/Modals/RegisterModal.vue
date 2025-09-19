<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import useModalStore from '@/store/modals';
import countries from '../../countries.json';
import { phoneMask } from '@/helpers/formatters'
import { isValidPhone } from '@/helpers/validations'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import useAuthStore from '@/store/auth'
import useGlobalStore from '@/store/global'
import z from 'zod'
import Modal from '@/components/Modal.vue'
import Input from '@/components/Input.vue'
import Button from '@/components/Button.vue'
import EyeIcon from '@/assets/icons/eye.svg?component'
import EyeClosedIcon from '@/assets/icons/eye-closed.svg?component'
import GoogleLogo from '@/assets/icons/google-logo.svg?component'
import { trans } from 'laravel-vue-i18n'

const global = useGlobalStore()
const modals = useModalStore()
const auth = useAuthStore()

const registerSchema = z.object({
  email: z.string({ required_error: trans('front.email-required') })
    .email({ message: trans('front.email-invalid') }),
  password: z.string({ required_error: trans('front.pass-required') })
    .min(6, { message: trans('front.pass-min-length') }),
  phone: z.string({ required_error: trans('front.phone-required') })
    .refine(isValidPhone, trans('front.phone-invalid')),
  affiliationCode: z.string().optional(),
  isLegalAge: z.boolean().refine((value) => value === true, 'Por favor, concorde com o nosso acordo de usuário'),
});

const data = reactive({
  email: '',
  name: '',
  password: '',
  phone: '',
  affiliationCode: '',
  captchaToken: '',
  isLegalAge: false,
})
const refCodeInput = reactive({
  show: false,
  disabled: false,
})
const showPassword = ref(false)
const selectedCountry = defineModel({
  default: {
    "name": "Brazil",
    "dial_code": "+55",
    "code": "BR",
    "flag": "https://upload.wikimedia.org/wikipedia/commons/0/05/Flag_of_Brazil.svg"
  }
})
const error = ref<string | null>(null)
const isLoading = ref(false)
const showCloseConfirmation = ref(false)

onMounted(() => {
  document.getElementById('email-input')!.focus()

  // Pegar o valor do parâmetro "ref" com o código do afiliado no url
  const url = new URL(window.location.href);
  if (url.searchParams.has('ref')) {
    data.affiliationCode = url.searchParams.get('ref')!
    refCodeInput.disabled = true
    refCodeInput.show = true
  }

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

async function register() {
  const validation = registerSchema.safeParse(data)

  if (!validation.success) {
    return error.value = validation.error.issues[0]?.message;
  }
  if (global.cloudflare_sitekey && !data.captchaToken) {
    return error.value = trans('front.captcha-required');
  }

  error.value = null
  isLoading.value = true

  await auth.register({
    email: data.email,
    name: data.name,
    birth_date: data.birth_date,
    address: data.address,
    password: data.password,
    phone: data.phone,
    country: selectedCountry.value.code,
    ddi: selectedCountry.value.dial_code,
    affiliation_code: data.affiliationCode,
    terms: true,
    captcha_token: data.captchaToken,
  })
    .then(res => {
      if (res.message === "Token successfully created") {
        // Success
        forceClose()
        modals.openDepositModal()
        window.notify(trans('front.welcome'), 'success')
      }
      else {
        window.notify(trans('front.account-not-created'), 'error')
        renderCaptcha()
      }
    })
    .catch((e) => {
      const errorMsg = e.response.data?.message
      if (errorMsg === "Captcha was not valid") {
        error.value = trans('front.captcha-required');
      }
      else if (e.response.status === 422) {
        // @ts-ignore
        const firstErrorMsg = Object.entries(e.response.data)?.[0]?.[1] as string
        error.value = firstErrorMsg
      }
      else {
        window.notify(trans('front.account-not-created'), 'error')
      }

      renderCaptcha()
    })
    .finally(() => {
      isLoading.value = false
    });
}

function close() {
  if (showCloseConfirmation.value) {
    forceClose()
  } else {
    showCloseConfirmation.value = true
  }
}
function forceClose() {
  showCloseConfirmation.value = false
  modals.showRegisterModal = false
}

const passwordStrength = computed(() => {
    let strength = 1
    if (/\d/.test(data.password)) strength += 1 // Numbers
    if (/[A-Z]/.test(data.password)) strength += 1 // Uppercase letter
    if (/[!@#$%^&*()_\-\+,.?":{}|\/<>]/.test(data.password)) strength += 1 // Special characters
    return Math.min(strength, 3)
})
</script>

<template>
  <Modal
    :on-close="close"
    :close-on-backdrop-click="!showCloseConfirmation"
    :modal-width="420"
    modal-class="auth-modal auth-modal--register"
  >
    <template v-if="!showCloseConfirmation">
      <img :src="global.logoURL" class="auth-modal__logo w-[200px] block mx-auto mb-10 mt-3" :alt="global.website_name">

      <form class="auth-modal__form" @submit.prevent="register">
        <Input
          :label="$t('front.name')"
          type="text"
          name="name"
          id="name-input"
          wrapper-class="auth-modal__input"
          required
          :modelValue="data.name"
          @update:modelValue="newValue => data.name = newValue"
        />
        <Input
          :label="$t('front.email')"
          type="email"
          name="email"
          id="email-input"
          wrapper-class="auth-modal__input"
          autocomplete="email"
          required
          :modelValue="data.email"
          @update:modelValue="newValue => data.email = newValue"
        />
        <Input
          :label="$t('front.pass')"
          :type="showPassword ? 'text' : 'password'"
          name="password"
          autocomplete="new-password"
          required
          wrapper-class="auth-modal__input"
          :modelValue="data.password"
          @update:modelValue="newValue => data.password = newValue"
        >
          <template v-slot:after_input>
            <div class="cursor-pointer p-0.5" @click="showPassword = !showPassword">
              <EyeClosedIcon v-if="showPassword" class="size-5" />
              <EyeIcon v-else class="size-5" />
            </div>
          </template>
          <template v-slot:after>
            <!-- Mostra a segurança da senha -->
            <div
                v-if="data.password.length"
                class="flex items-center gap-2 text-xs -mt-1.5 mb-4"
                :class="['text-rose-600','text-amber-400','text-emerald-400'][passwordStrength-1]"
            >
                <div v-for="index in 3" class="h-[5px] flex-1 rounded-full" :class="passwordStrength >= index ? 'bg-current' : 'bg-gray-400/60'"></div>
                <span class="shrink font-medium">{{ ['Muito fraca', 'Média', 'Segura'][passwordStrength-1] }}</span>
            </div>
          </template>
        </Input>

        <Input
          :label="$t('front.phone')"
          type="tel"
          name="phone"
          autocomplete="tel"
          wrapper-class="auth-modal__input"
          required
          maxlength="15"
          placeholder="(  ) _____-____"
          :modelValue="data.phone"
          @update:modelValue="newValue => data.phone = phoneMask(newValue)"
        >
          <template v-slot:before_input>
            <Listbox v-model="selectedCountry" as="div" class="relative shrink-0">
              <ListboxButton class="flex items-center gap-1" tabindex="-1">
                <img :src="selectedCountry.flag" height="18" width="21">
                {{ selectedCountry.dial_code }}
              </ListboxButton>
              <ListboxOptions class="bg-[#424344] rounded-md py-1 min-w-[120px] max-h-[220px] absolute left-0 top-full z-10 overflow-auto">
                <ListboxOption
                  v-for="contry in countries"
                  :key="contry.code"
                  :value="contry"
                  class="hover:bg-white/5 px-4 py-3 cursor-pointer flex items-center gap-2"
                >
                  <img :src="contry.flag" height="18" width="21">
                  {{ contry.dial_code }}
                </ListboxOption>
              </ListboxOptions>
            </Listbox>
          </template>
        </Input>

        <Input
          :label="$t('front.ref-code') + ':'"
          type="text"
          autocomplete="off"
          wrapper-class="auth-modal__input"
          :modelValue="data.affiliationCode"
          @update:modelValue="newValue => data.affiliationCode = newValue"
          :disabled="refCodeInput.disabled"
          v-if="refCodeInput.show"
        />

        <Button type="button" class="w-full !bg-transparent" @click="refCodeInput.show = true" v-if="!refCodeInput.show">{{ $t('front.ref-code') }}</Button>

        <div class="cf-turnstile w-fit my-4 mx-auto"></div>

        <label class="mt-4 text-sm text-neutral-300 flex items-center gap-2">
            <input type="checkbox" class="accent-primary scale-110" v-model="data.isLegalAge">Eu confirmo que tenho pelo menos 18 anos
        </label>

        <p class="my-4 text-center text-xs opacity-80">{{ $t('front.terms-agree')}} <RouterLink to="/terms" class="text-primary">{{ $t('front.terms-conditions') }}</RouterLink></p>

        <p class="auth-modal__error bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-3.5 my-4" v-if="error">{{ error }}</p>

        <Button
          type="submit"
          primary
          class="auth-modal__submit-button w-full text-lg font-semibold py-3.5 mt-4"
          @click="register"
          :disabled="isLoading"
        >{{ $t('front.create-account') }}</Button>
      </form>

      <div v-if="global.google_login_enabled" class="text-neutral-400/90 border-t border-current pt-8 mt-8 relative">
        <span class="absolute top-0 left-2/4 -translate-x-2/4 -translate-y-2/4 bg-background px-4 py-1">OU</span>

        <a href="/auth/google/redirect">
          <Button class="flex-1 w-full py-3 text-base text-neutral-300 bg-transparent border border-current">
            <GoogleLogo class="size-5 text-current"/>
            {{ $t('front.login-with', { service: 'Google' }) }}</Button>
        </a>
      </div>

      <p class="mt-10 text-center text-base">
        {{ $t('front.already-has-account') }}
        <a @click.prevent="modals.openLoginModal()" class="text-primary">{{ $t('front.login-enter') }}</a>
      </p>
    </template>

    <template v-else>
      <div class="text-center">
        <h2 class="text-3xl font-medium text-primary">{{ $t('front.cancel-confirmation') }}</h2>
        <p class="text-gray-400 text-lg mt-4 mb-10">{{ $t('front.cancel-confirmation-subheading', { bonus: global.bonus_percent+'%' }) }}.</p>
        <Button
          primary
          class="w-full text-lg font-semibold py-3.5 my-4"
          @click="showCloseConfirmation = false"
        >{{ $t('front.continue-registration') }}</Button>
        <Button
          outlined
          class="w-full opacity-60"
          @click="close"
        >{{ $t('front.cancel') }}</Button>
      </div>
    </template>
  </Modal>
</template>

<style>
  .p-dropdown-trigger {
    display: none;
  }
</style>
