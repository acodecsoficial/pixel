<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'
import GameListItem from '@/components/GameListItem.vue'
import Spinner from '@/components/Spinner.vue'
import BackIcon from '@/assets/icons/back.svg?component'

const route = useRoute()

const data = ref<any>({})
const isLoading = ref(false)

onMounted(() => {
  getData()
})

watch(() => route.params.provider, () => {
  getData()
})

async function getData () {
  const { provider } = route.params
  isLoading.value = true

  const res = await api.get(`/casino/categories/${provider}`)
    .then(res => {
      data.value = res.data
    })
    .finally(() => {
      isLoading.value = false
    })
}
</script>

<template>
  <Spinner v-if="isLoading" class="my-24 mx-auto" />

  <template v-else>
    <header class="mb-8 flex items-center gap-3">
      <a @click.prevent="$router.go(-1)">
        <BackIcon class="icon size-8 p-1.5" />
      </a>

      <h2 class="text-3xl font-medium grow">{{ $t('front.games-from', { name: data?.name ?? '' }) }}</h2>

      <span class="text-sm text-gray-400">{{  $t('front.games-count', { count: data?.games?.length ?? '' }) }}</span>
    </header>

    <div v-if="data.games?.length === 0" class="text-xl text-center text-gray-300 py-12">
      {{ $t('front.no-games') }}
    </div>

    <div v-else class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4 lg:gap-5">
      <GameListItem
        v-for="game in data?.games"
        :name="game.name"
        :slug="'/' + game.slug"
        :provider="game.provider_name"
        :image="game.image"
      />
    </div>
  </template>
</template>
