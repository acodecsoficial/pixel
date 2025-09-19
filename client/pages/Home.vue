<script setup lang="ts">
import { ref, onMounted, provide, toRaw } from 'vue'
import api from '@/services/api'
import Hero from '@/components/Hero.vue'
import SearchBox from '@/components/SearchBox.vue'
import HomeRecomendations from '@/components/HomeRecomendations.vue'
import TopWinners from '@/components/TopWinners.vue'
import TopGames from '@/components/TopGames.vue'
import GameListSection from '@/components/GameListSection.vue'
// @ts-ignore
import ProvidersSection from '@/components/ProvidersSection.vue'
import HomeSkeletonLoading from '@/components/HomeSkeletonLoading.vue'
import type { HomeData } from '@/interfaces'

const data = ref<HomeData>({})
const isLoading = ref(false)

provide('home-data', data);

onMounted(async function () {
  if ('homeDataCache' in window) {
    data.value = window.homeDataCache as any
  } else {
    isLoading.value = true

    await api.get('/casino/home-games')
      .then(res => {
        data.value = res.data
        // @ts-ignore
        window.homeDataCache = toRaw(data.value)
      })
      .finally(() => {
        isLoading.value = false
      })
  }
})
</script>

<template>
  <div class="home-page">
    <Hero />
    <SearchBox />
    <TopWinners />
    <HomeRecomendations />
    <TopGames />

    

    <template
      v-if="!isLoading"
      v-for="category, index in data.categories"
    >
      <GameListSection
        v-if="category.games.length >= 1"
        :name="category.category_name"
        :more="'/casino/' + encodeURIComponent(category.category_provider)"
        :showCount="true"
        :games="category.games"
      />
    </template>
    <HomeSkeletonLoading v-else />

    <ProvidersSection />
  </div>
</template>


