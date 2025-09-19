<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import z from 'zod'
import api from '@/services/api'
import useAuthStore from '@/store/auth'
import ChevronIcon from '@/assets/icons/chevron-right.svg?component'
import EmailIcon from '@/assets/icons/email.svg?component'
import Spinner from '@/components/Spinner.vue'
import RequiresLogin from '@/components/RequiresLogin.vue'
import countries from '../../../countries.json'
import { Listbox, ListboxButton, ListboxOptions, ListboxOption } from '@headlessui/vue'
import { isValidCPF, isValidPhone } from '@/helpers/validations'
import { cpfMask, phoneMask } from '@/helpers/formatters'
import { trans } from 'laravel-vue-i18n'

const auth = useAuthStore()

const schema = z.object({
  phone: z.string({ required_error: trans('front.phone-required') })
    .refine(isValidPhone, trans('front.phone-invalid')),
  ddi: z.string()
    .length(3, trans('front.ddi-invalid')),
})

const data = reactive({
  email: auth.user?.email,
  phone: auth.user?.phone,
  ddi: auth.user?.ddi,
})
const error = ref<string | null>(null)
const isLoading = ref(false)

const selectedCountry = defineModel({
  default: {
    "name": "Brazil",
    "dial_code": "+55",
    "code": "BR",
    "flag": "https://upload.wikimedia.org/wikipedia/commons/0/05/Flag_of_Brazil.svg"
  }
})
watch(selectedCountry, () => {
  data.ddi = selectedCountry.value.dial_code
})

watch(() => auth.isAuthenticated, () => {
  if (auth.user) {
    data.email = auth.user?.email
    data.phone = auth.user?.phone
    data.ddi = auth.user?.ddi
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
  })
    .then(res => {
      window.notify(trans('front.account.saved'));

      auth.user.phone = data.phone
      auth.user.ddi = data.ddi
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
        <EmailIcon class="size-6 ml-1 opacity-75" />
        <h2 class="text-2xl font-semibold">{{ $t('front.edit') + ' ' + $t('front.account.contact-infos').toLowerCase() }}</h2>
      </div>

      <form @submit.prevent="save" class="py-7 flex flex-col gap-3">
        <Input
          :disabled="true"
          :label="$t('front.email')"
          type="email"
          :modelValue="auth.user?.email"
          @update:modelValue="newValue => {}"
        />

        <Input
          :label="$t('front.phone')"
          type="tel"
          name="phone"
          autocomplete="tel"
          maxlength="15"
          placeholder="(  ) _____-____"
          :modelValue="phoneMask(data.phone)"
          @update:modelValue="newValue => data.phone = newValue"
        >
          <template v-slot:before_input>
            <Listbox v-model="selectedCountry" as="div" class="relative shrink-0">
              <ListboxButton class="flex items-center gap-1">
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

        <div class="text-rose-400 mb-3" v-if="error">{{ error }}</div>

        <Button type="submit" primary class="text-lg font-medium mt-2" :disabled="isLoading">
          <Spinner v-if="isLoading" class="size-7" />
          <span v-else>{{ $t('front.account.save') }}</span>
        </Button>
      </form>

    </div>
  </RequiresLogin>
</template>
