<script setup lang="ts">
import { computed, inject, Ref } from 'vue'
import type { HomeData } from '@/interfaces'

const data = inject<Ref<HomeData>>('home-data')
const games = computed(() => data?.value?.top_games ?? [])
</script>

<template>
  <GameListSection
    v-if="(games?.length ?? 0) >= 4"
    :name="$t('front.top-games')"
    :isTopGames="true"
    :showCount="false"
    :games="games"
    class="top-games"
  />
</template>
