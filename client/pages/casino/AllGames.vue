<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue'
import api from '@/services/api'
import GameListItem from '@/components/GameListItem.vue'
import SearchBox from '@/components/SearchBox.vue'
import Spinner from '@/components/Spinner.vue'
import BackIcon from '@/assets/icons/back.svg?component'

const state = reactive({
  data: [] as any[],
  page: 0,
  isLoading: false,
  canLoadMore: true,
  totalCount: 0,
})
const spanRef = ref<HTMLSpanElement|null>(null)

onMounted(function () {
  // Carregar mais jogos quando o usuário chegar no final da página
  const observer = new IntersectionObserver((entries, observer) => {
    const entry = entries[0]
    if (entry.isIntersecting && !state.isLoading && state.canLoadMore) {
      loadMore()
    }
  }, {
    root: null,
    rootMargin: '0px',
    threshold: 1.0 // 100% do elemento deve estar visível
  })
  observer.observe(spanRef.value!);
})

function loadMore() {
  state.isLoading = true
  state.page++

  api.get(`/casino/games?page=${state.page}`)
    .then(res => {
      // console.log('res.data', res.data)
      state.data = state.data.concat(res.data.data)
      state.totalCount = res.data.total
      state.canLoadMore = res.data.next_page_url !== null
    })
    .finally(() => {
      state.isLoading = false
    })
}
</script>

<template>
  <header class="mb-8 flex items-center gap-3">
    <a @click.prevent="$router.go(-1)">
      <BackIcon class="icon size-8 p-1.5" />
    </a>

    <h2 class="text-3xl font-medium grow">{{ $t('front.all-games') }}</h2>

    <span class="text-sm text-gray-400" v-if="state.totalCount">{{ $t('front.games-count', { count: state.totalCount+'' }) }}</span>
  </header>

  <SearchBox />

  <div v-if="state.data?.length === 0 && !state.isLoading" class="text-xl text-center text-gray-300 py-12">
    {{ $t('front.no-games') }}
  </div>

  <div v-else class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4 lg:gap-5">
    <GameListItem
      v-for="game in state?.data"
      :name="game.name"
      :slug="'/' + game.slug"
      :image="game.image"
      :provider="game.provider_name"
    />
  </div>

  <Spinner v-if="state.isLoading" class="my-10 mx-auto" />
  <span ref="spanRef" class="w-full h-1"></span>
</template>
