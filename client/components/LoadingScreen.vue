<script setup lang="ts">
import { computed } from 'vue'
import useGlobalStore from '@/store/global';

const global = useGlobalStore()
const isAppLoading = computed(() => !global.logo)
</script>

<template>
  <div
    v-if="isAppLoading"
    class="fixed inset-0 z-100 flex justify-center items-center"
  >
    <Spinner />
  </div>

  <slot v-else />
</template>
