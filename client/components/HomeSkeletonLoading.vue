<script setup lang="ts">
const list = Array.from({ length: 4 }, (_, i) => i)
const games = Array.from({ length: 10 }, (_, i) => ({
  name: 'Jogo de teste',
  slug: 'jogo-de-teste',
  image: 'https://via.placeholder.com/300x400',
  provider_name: 'TEST'
}))
</script>

<template>
  <GameListSection
    v-for="(_, i) in list"
    :key="i"
    name="Jogos da Evolution"
    :games="games"
    :isSkeleton="true"
  />
</template>
