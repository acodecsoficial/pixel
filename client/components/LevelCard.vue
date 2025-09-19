<script setup lang="ts">
const progress = 2
</script>

<template>
  <div class="bg-surface rounded-custom-max my-8 p-5 pb-6">
    <div class="text-sm">Nível 7</div>
    <div class="text-xs opacity-65">Desconto: 5%</div>

    <div class="bg-white/10 w-full h-[6px] rounded-full relative mt-7">
      <div class="bg-primary h-[6px] rounded-full" :style="{ width: progress+'%' }"></div>

      <div class="level-tooltip bg-primary text-xs text-white rounded-md px-1 py-0.5 absolute bottom-[calc(100%+7px)] -translate-x-2/4 -ml-[2px]" :style="{ left: progress+'%' }">{{ progress }}%</div>
    </div>
  </div>
</template>

<style scoped>
  .level-tooltip::before {
    @apply bg-inherit absolute rounded-br-[2px] size-[7px] -bottom-[3px] right-[13px] rotate-45;
    content: '';
  }
</style>
