<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import z from 'zod'
import api from '@/services/api'
import useAuthStore from '@/store/auth'
import ChevronIcon from '@/assets/icons/chevron-right.svg?component'
import DocumentIcon from '@/assets/icons/document.svg?component'
import RequiresLogin from '@/components/RequiresLogin.vue'
import { isValidCPF, isValidPhone } from '@/helpers/validations'
import { cpfMask } from '@/helpers/formatters'
import { trans } from 'laravel-vue-i18n'

const auth = useAuthStore()

const schema = z.object({
  username: z.string({ required_error: trans('front.usernanme-required') })
    .min(3, { message: trans('front.username-min-length', { min: '3' }) })
    .max(20, { message: trans('front.username-max-length', { max: '20' }) })
    .refine(value => /^[a-zA-Z0-9]*$/.test(value), trans('front.username-invalid')),
  cpf: z.string({ required_error: trans('front.cpf-required') })
    .optional()
    .nullable()
    .refine((value) => value ? isValidCPF(value) : true, trans('front.cpf-invalid')),
  name: z.string()
    .max(80, { message: trans('front.name-max-length', { max: '80' }) })
})

const data = reactive({
  name: auth.user?.name,
  username: auth.user?.username,
  cpf: auth.user?.cpf,
})
const error = ref<string | null>(null)
const isLoading = ref(false)

watch(() => auth.isAuthenticated, () => {
  if (auth.user) {
    data.name = auth.user?.name
    data.username = auth.user?.username
    data.cpf = auth.user?.cpf
  }
})

function save() {
  const validation = schema.safeParse(data)

  if (!validation.success) {
    return error.value = validation.error.issues[0]?.message;
  }

  error.value = null
  isLoading.value = true

  api.patch(`/user`, {
    ...data,
    cpf: data.cpf?.replace(/[.-]/g, ''),
  })
    .then(res => {
      window.notify(trans('front.account.saved'));

      auth.user.name = data.name
      auth.user.username = data.username
      auth.user.cpf = data.cpf
    })
    .catch(err => {
      if (err.response.status === 422) {
        // @ts-ignore
        const firstErrorMsg = Object.entries(err.response.data)?.[0]?.[1] as string
        error.value = firstErrorMsg
      }
      else if (err.response.data?.message) {
        error.value = err.response.data.message
      }

      window.notify(trans('front.account.not-saved'), 'error');
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
        <DocumentIcon class="size-6 ml-1 opacity-75" />
        <h2 class="text-2xl font-semibold">{{ $t('front.edit') + ' ' + $t('front.account.personal-infos').toLowerCase() }}</h2>
      </div>

      <form @submit.prevent="save" class="py-7 flex flex-col gap-3">
        <Input
          :label="$t('front.name')"
          type="text"
          :modelValue="auth.user?.name"
          @update:modelValue="newValue => data.name = newValue"
        />

        <Input
          :label="$t('front.username')"
          type="text"
          :modelValue="data.username"
          @update:modelValue="newValue => data.username = newValue"
        />

        <div class="mb-3">
          <Input
            :label="$t('front.cpf')"
            type="text"
            maxlength="14"
            placeholder="___.___.___-__"
            :disabled="!!auth.user.cpf"
            :modelValue="cpfMask(data.cpf ?? '')"
            @update:modelValue="newValue => data.cpf = newValue"
          />
          <div class="text-sm text-gray-400 -mt-2.5">{{ $t('front.cpf-one-time') }}</div>
        </div>

        <div class="text-rose-400 mb-3" v-if="error">{{ error }}</div>

        <Button type="submit" primary class="text-lg font-medium mt-2" :disabled="isLoading">
          <Spinner v-if="isLoading" class="size-7" />
          <span v-else>{{ $t('front.account.save') }}</span>
        </Button>
      </form>

    </div>
  </RequiresLogin>
</template>
