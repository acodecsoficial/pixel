<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
import api from '@/services/api'
import useAuthStore from '@/store/auth'
import useGlobalStore from '@/store/global'
import RequiresLogin from '@/components/RequiresLogin.vue'
import { trans } from 'laravel-vue-i18n'

const global = useGlobalStore()
const auth = useAuthStore()

const url = ref('')
const isLoading = ref(false)
const error = ref<string | null>(null)
const iframeRef = ref<HTMLElement | null>(null)
let parentElementClassname = ''

onMounted(() => {
  if (auth.isAuthenticated) getSportLaunchURL()

  // Fazer a página carregar em tela cheia
  const parentElement = document.querySelector('main')!.children[0]
  parentElementClassname = parentElement.className!
  parentElement.className = ''
})

onUnmounted(() => {
  document.querySelector('main')!.children[0].className! = parentElementClassname
})

watch(() => auth.isAuthenticated, () => {
  if (auth.isAuthenticated) getSportLaunchURL()
})

function getSportLaunchURL() {
  isLoading.value = true
  error.value = null

  api.post('/sportbook/launch', {
    user_id: auth.user!.id
  })
    .then(res => {
      url.value = res.data.launchUrl
    })
    .catch(e => {
      error.value = trans('front.page-load-error')
    })
    .finally(() => {
      isLoading.value = false
    })
}
</script>

<template>
  <RequiresLogin>
    <img
      v-if="isLoading"
      :src="global.logoURL"
      class="h-[40px] mx-auto my-32 animate-[pulsar_1s_linear_infinite]"
      :alt="global.website_name"
    >
    <div
      v-if="error"
      class="text-xl text-center text-rose-400 py-32"
    >
      {{ error }}
      <Button outlined @click="getSportLaunchURL" class="font-normal text-base opacity-90 mx-auto mt-4">{{ $t('front.try-again') }}</Button>
    </div>

    <iframe
      ref="iframeRef"
      :src="url"
      v-if="url && !isLoading && !error"
      class="w-full bg-black"
      :style="{
        '--top': (iframeRef?.getBoundingClientRect().top!) + 'px',
        'height': 'calc(100vh - var(--top, 100px))',
      }"
      frameborder="0"
    />
  </RequiresLogin>
</template>
