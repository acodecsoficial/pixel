<script setup lang="ts">
import { ref, watch } from 'vue'
import api from '@/services/api'
import useGlobalStore from '@/store/global'
import { debounce } from '@/helpers/debounce'
import GameListItem from '@/components/GameListItem.vue'
import closeIcon from '@/assets/icons/close.svg?url'
import SearchIcon from '@/assets/icons/search.svg?component'
import LoadingIcon from '@/assets/icons/loading.svg?component'
import { trans } from 'laravel-vue-i18n'

const global = useGlobalStore()
const query = defineModel({ default: '' })
const showResultPanel = ref(false)
const isLoading = ref(false)
const errorMsg = ref('')
const inputRef = ref<HTMLInputElement | null>(null)
const results = ref<any[]>([])

watch(query, function () {
  showResultPanel.value = (query.value.length >= 1)

  if (query.value.length < 3) {
    errorMsg.value = trans('front.search.min-length', { length: '3' })
  } else {
    isLoading.value = true
    errorMsg.value = ''
    search()
  }
})

watch(() => global.showHeaderSearchBox, () => {
  if (global.showHeaderSearchBox === true) {
    inputRef.value?.focus()
  } else {
    showResultPanel.value = false
  }
})

const search = debounce(() => {
  isLoading.value = true
  errorMsg.value = ''
  results.value = []

  api.get(`/casino/games?search=${query.value}`)
    .then((res) => {
      const items = res.data.data
      results.value = items
      // console.log('search result', items)

      if (items.length === 0) {
        errorMsg.value = trans('front.search.no-results')
      }
    })
    .catch((err) => {
      errorMsg.value = trans('front.search.error')
    })
    .finally(() => {
      isLoading.value = false
    })
}, 300)

function onInputFocus() {
  const mainElem = document.querySelector('main')
  const headerHeight = document.querySelector('header')?.parentElement!.getBoundingClientRect().height!
  const topPadding = 20
  const top = inputRef.value!.getBoundingClientRect().top + mainElem!.scrollTop - headerHeight - topPadding
  mainElem!.scrollTo({ top, behavior: 'smooth' })

  if (query.value.length > 0) {
    showResultPanel.value = true
  }
}

function closeSearch() {
  showResultPanel.value = false
  global.showHeaderSearchBox = false
}
</script>

<template>
  <div class="relative my-8">

    <div class="search-box bg-white/10 focus-within:bg-white/10 rounded-custom flex items-center pl-5 pr-2 relative z-20">
      <component :is="isLoading ? LoadingIcon : SearchIcon" class="icon size-5 text-white/70" :class="{ 'animate-spin': isLoading }" />
      <input
        ref="inputRef"
        type="text"
        v-model="query"
        class="search-box__input bg-transparent px-4 py-4 outline-none grow"
        :placeholder="$t('front.search.placeholder')"
        autocomplete="off"
        @focus="onInputFocus()"
        @keyup.escape="$emit('onForceClose')"
      >
      <img :src="closeIcon" class="size-6 p-1 cursor-pointer" v-if="query" @click="query = ''">
    </div>

    <span class="backdrop bg-black/50 fixed top-0 left-0 bottom-0 right-0 z-10" v-if="showResultPanel" @click="showResultPanel = false" />

    <div
      v-if="showResultPanel"
      class="search-box__dropdown bg-[#323637] rounded-md absolute top-[calc(100%+10px)] max-h-[calc(100vh-200px)] left-0 right-0 p-4 z-40 overflow-auto hide-scrollbar"
      @click="closeSearch"
    >
      <span v-if="errorMsg" class="search-box__error text-amber-500 block text-center">{{ errorMsg }}</span>

      <div v-if="isLoading" class="search-box__skeleton grid gap-3 grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
        <span class="bg-black/30 rounded-md aspect-[166/193] flex-[1_1_0px] animate-pulse" v-for="index in 6" :key="index" />
      </div>

      <div
        v-if="!isLoading && !errorMsg && results.length >= 1"
        id="search-results"
        class="search-box__results grid gap-3 grid-cols-3 md:grid-cols-4 lg:grid-cols-6"
      >
        <GameListItem
          v-for="item in results"
          :key="item.id"
          :slug="item.slug"
          :name="item.name"
          :image="item.image"
          :provider="item.provider_name"
          class="search-box__result-item"
        />
      </div>
    </div>
  </div>
</template>
