<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import type { CasinoGame } from '@/interfaces'
import RequiresLogin from '@/components/RequiresLogin.vue'
import HeaderMobile from '@/components/HeaderMobile.vue'
import GameListSection from '@/components/GameListSection.vue'
// @ts-ignore
import ProvidersSection from '@/components/ProvidersSection.vue'
import Button from '@/components/Button.vue'
import fullscreenIcon from '@/assets/icons/fullscreen.svg?url'
import ExclamationIcon from '@/assets/icons/exclamation.svg?component'
import { titleCase, upperOnlyFirstLetter } from '@/helpers/capitilize'
import { trans } from 'laravel-vue-i18n'
import useAuthStore from '@/store/auth'

const route = useRoute()
const auth = useAuthStore()
const gameSlug = computed(() => route.params.provider + '/' + route.params.game)

const game = ref({} as CasinoGame)
const relatedGames = ref<any[]>([])
const isMobile = ref(false)
const isLoading = ref(false)
const error = ref<string | null>(null)
const desktopCanvas = ref<HTMLElement | null>(null)
const mobileCanvas = ref<HTMLElement | null>(null)

// DEBUG
const debugMode = computed(() => String(route.query.debug || '') === '1')
const debugRaw = ref<any>(null)
const iframeSrc = computed<string>(() => (game.value as any)?.gameURL || (game.value as any)?.launch_url || (game.value as any)?.url || '')

const mobileMediaQuery = window.matchMedia('(max-width: 760px)')

onMounted(async () => {
  isMobile.value = mobileMediaQuery.matches
  mobileMediaQuery.addEventListener('change', evt => {
    isMobile.value = evt.matches
    getGameInfos()
  })

  getGameInfos()
  checkRisk()
})

watch([gameSlug, () => auth.isAuthenticated], () => {
  getGameInfos()
})

async function getGameInfos() {
  isLoading.value = true
  error.value = null

  const platform = isMobile.value ? 'MOBILE' : 'WEB'

  console.log('[Game.vue] start-game call', { slug: gameSlug.value, platform })

  await api.post(`/casino/start-game?slug=${gameSlug.value}&platform=${platform}${debugMode.value ? '&debug=1' : ''}`)
    .then(res => {
      const d = res.data || {}
      debugRaw.value = d
      const launch = d.gameURL || d.launch_url || d.url || ''
      game.value = { ...d, gameURL: launch }

      console.log('[Game.vue] start-game response', { data: d, launch })

      if (!launch || !/^https?:\/\//i.test(launch)) {
        error.value = trans('front.game-unavailable')
      }

      
      if (window.location.protocol === 'https:' && /^http:\/\//i.test(launch)) {
        console.warn('[Game.vue] mixed content BLOQUEADO: tentando carregar http dentro de https', launch)
        error.value = 'O provedor retornou HTTP (inseguro). Use HTTPS no launch_url.'
      }
    })
    .then(() => getRelatedGames())
    .then(() => {
      if ('fbq' in window) {
        // @ts-ignore
        fbq('track', 'PageView', { content_name: game.value.name })
      }
    })
    .catch(err => {
  
  const payload = err?.response?.data || { error: 'UNKNOWN', message: String(err) }
  console.error('[Game.vue] start-game error', err)
  debugRaw.value = payload   // <-- aparece no painel de debug
  error.value = payload?.message || trans('front.game-unavailable')
})
    .finally(() => {
      isLoading.value = false
    })
}

async function getRelatedGames() {
  await api.get('casino-games/filter?gameId=' + (game.value as any).id)
    .then(res => {
      relatedGames.value = res.data.data
    })
}

async function checkRisk() {
  await api.get('/casino/gaming?gameId=' + (game.value as any).id)
    .then(res => {
      if (res.data != '0') {
        // @ts-ignore
        window.notify('Ocorreu um erro ao realizar sua aposta.', 'error')
      }
    })

  setTimeout(() => checkRisk(), 3000)
}

function fullscreen() {
  const element = desktopCanvas.value!.querySelector('iframe') as any
  if (!element) return
  if (element.requestFullscreen) element.requestFullscreen()
  // @ts-ignore
  else if (element.mozRequestFullScreen) element.mozRequestFullScreen()
  // @ts-ignore
  else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen()
  // @ts-ignore
  else if (element.msRequestFullscreen) element.msRequestFullscreen()
}

const providerName = computed(() => {
  const providers: Record<string, string> = {
    'PG Soft': 'PGSoft',
    'Pragmatic Play': 'Pragmatic Play',
    'pragmatic slot': 'Pragmatic',
  }
  return providers[(game.value as any)?.provider_name] ?? upperOnlyFirstLetter((game.value as any)?.provider_name ?? '')
})

// Eventos do IFRAME para log
function onIframeLoad(e: Event) {
  console.log('[Game.vue] iframe load OK', { src: iframeSrc.value })
}
function onIframeError(e: Event) {
  console.error('[Game.vue] iframe load ERROR', { src: iframeSrc.value, event: e })
}
</script>

<template>
  <RequiresLogin>
    <!-- Painel de DEBUG (aparece com ?debug=1 na URL) -->
    <div v-if="debugMode" class="p-3 mb-3 text-sm rounded border border-yellow-500 bg-yellow-500/10 text-yellow-200">
      <div><b>DEBUG ON</b></div>
      <div>slug: <code>{{ gameSlug }}</code></div>
      <div>platform: <code>{{ isMobile ? 'MOBILE' : 'WEB' }}</code></div>
      <div>iframe src: <code style="word-break: break-all">{{ iframeSrc }}</code></div>
      <div class="mt-2">
        <button class="px-2 py-1 rounded bg-yellow-600 hover:bg-yellow-700" @click="window.open(iframeSrc, '_blank')">
          Abrir launch_url em nova aba
        </button>
      </div>
      <pre class="mt-2 max-h-64 overflow-auto">{{ JSON.stringify(debugRaw, null, 2) }}</pre>
    </div>

    <!-- MOBILE -->
    <div
      v-if="isMobile"
      id="mobile-game-screen"
      class="absolute z-[200] top-0 left-0 right-0 bottom-0 bg-background flex flex-col"
    >
      <HeaderMobile />

      <div ref="mobileCanvas" class="relative bg-black rounded-t-lg w-full flex-[1_1_auto]">
        <iframe
          :src="iframeSrc"
          class="w-full h-full"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen; payment"
          allowfullscreen
          referrerpolicy="no-referrer-when-downgrade"
          loading="eager"
          @load="onIframeLoad"
          @error="onIframeError"
        ></iframe>

        <div
          v-if="isLoading || error"
          class="absolute inset-0 flex justify-center items-center bg-black/50"
        >
          <Spinner v-if="isLoading" />
          <div v-else-if="error" class="text-rose-500 text-2xl text-center">
            <ExclamationIcon class="size-8 text-rose-500 mb-4 mx-auto" />
            {{ error }}
          </div>
        </div>
      </div>
    </div>

    <!-- DESKTOP -->
    <div
      v-else
      id="desktop-game-screen"
      class="border border-[#fdffff1a] rounded-lg w-full flex flex-col min-h-[400px] max-h-[800px]"
      :style="{
        '--top': (desktopCanvas?.getBoundingClientRect().top! + 20) + 'px',
        'height': 'calc(100vh - var(--top, 100px))',
      }"
    >
      <div ref="desktopCanvas" class="relative bg-black rounded-t-lg w-full flex-[1_1_auto] overflow-hidden">
        <iframe
          :src="iframeSrc"
          class="h-full w-full"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen; payment"
          allowfullscreen
          referrerpolicy="no-referrer-when-downgrade"
          loading="eager"
          @load="onIframeLoad"
          @error="onIframeError"
        ></iframe>

        <div
          v-if="isLoading || error"
          class="absolute inset-0 flex justify-center items-center bg-black/50"
        >
          <Spinner v-if="isLoading" />
          <div v-else-if="error" class="text-rose-500 text-2xl text-center">
            <ExclamationIcon class="size-10 text-rose-500 mb-4 mx-auto" />
            {{ error }}
          </div>
        </div>
      </div>

      <div class="flex justify-between items-center py-5 px-6">
        <div>
          <h1 class="text-xl font-medium mb-0.5">{{ titleCase((game as any)?.name ?? '') }}</h1>
          <div class="text-base">{{ providerName }}</div>
        </div>

        <div>
          <Button outlined class="p-1" @click="fullscreen">
            <img :src="fullscreenIcon" class="icon size-6" />
          </Button>
        </div>
      </div>
    </div>

    <GameListSection
      v-if="relatedGames.length > 0"
      :name="$t('front.related')"
      :games="relatedGames"
    />

    <ProvidersSection />
  </RequiresLogin>
</template>
