<script setup lang="ts">
import { ref } from 'vue'
import z from 'zod'
import api from '@/services/api'
import useAuthStore from '@/store/auth'
import useModalStore from '@/store/modals'
import ChevronIcon from '@/assets/icons/chevron-right.svg?component'
import RequiresLogin from '@/components/RequiresLogin.vue'
import KeyIcon from '@/assets/icons/key.svg?component'
import { trans } from 'laravel-vue-i18n'

const auth = useAuthStore()
const modals = useModalStore()

const isLoading = ref(false)

const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const passwordError = ref('')

function changePassword() {
  if (!currentPassword.value || !newPassword.value || !confirmPassword.value) {
    return passwordError.value = trans('front.account.all-fields-required')
  }
  if (newPassword.value.length < 6) {
    return passwordError.value = trans('front.account.pass-min-length', { min: '6' })
  }
  if (newPassword.value !== confirmPassword.value) {
    return passwordError.value = trans('front.account.pass-confirmation')
  }

  isLoading.value = true
  passwordError.value = ''

  api.post(`/user/change-password`, {
    current_password: currentPassword.value,
    new_password: newPassword.value,
    new_password_confirmation: confirmPassword.value
  })
    .then(res => {
      if (res.data.message !== 'Senha alterada com sucesso!') {
        return window.notify(trans('front.account.change-pass-error'), 'error')
      }

      window.notify(trans('front.account.pass-changed'))
      currentPassword.value = ''
      newPassword.value = ''
      confirmPassword.value = ''
    })
    .catch(err => {
      if (err.response.status === 422) {
        // @ts-ignore
        const firstErrorMsg = Object.entries(err.response.data)?.[0]?.[1] as string
        passwordError.value = firstErrorMsg
      }

      window.notify(trans('front.account.change-pass-error'), 'error')
    })
    .finally(() => {
      isLoading.value = false
    })
}
</script>

<template>
  <RequiresLogin>
    <div class="max-w-[500px] mx-auto mt-5">
      <div class="flex items-center gap-2">
        <RouterLink to="/user/account">
          <ChevronIcon class="icon size-8 p-1.5 -ml-1.5 rotate-180" />
        </RouterLink>
        <KeyIcon class="size-5 ml-1 opacity-75" />
        <h2 class="text-2xl font-semibold">{{ $t('front.account.change-pass') }}</h2>
      </div>

      <form @submit.prevent="changePassword" class="py-7 flex flex-col gap-3">
        <Input
          :label="$t('front.account.old-pass')"
          type="password"
          name="old-password"
          autocomplete="current-password"
          required
          :modelValue="currentPassword"
          @update:modelValue="newValue => currentPassword = newValue"
        />
        <Input
          :label="$t('front.account.new-pass')"
          type="password"
          name="password"
          required
          autocomplete="off"
          minlength="6"
          :modelValue="newPassword"
          @update:modelValue="newValue => newPassword = newValue"
        />
        <Input
          :label="$t('front.account.confirm-new-pass')"
          type="password"
          name="confirm-password"
          autocomplete="off"
          required
          :modelValue="confirmPassword"
          @update:modelValue="newValue => confirmPassword = newValue"
        />

        <div class="text-rose-400 mb-3" v-if="passwordError">{{ passwordError }}</div>

          <Button primary class="text-lg font-medium" type="submit" :disabled="isLoading">
            <Spinner v-if="isLoading" class="size-7" />
            <span v-else>{{ $t('front.account.save') }}</span>
          </Button>

        <a class="text-primary mt-6 mx-auto" @click.prevent="modals.openForgotPasswordModal()">{{ $t('front.forgot-my-pass')}}</a>

        <input type="email" name="email" :value="auth.user.email" disabled style="display:none;">
      </form>

    </div>
  </RequiresLogin>
</template>
