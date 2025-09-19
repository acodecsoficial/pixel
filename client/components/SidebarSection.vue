<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import DownIcon from '@/assets/icons/down.svg?component'

const props = defineProps<{
  label: string
}>()

const show = ref(true)
const listRef = ref<HTMLElement|null>(null)
let listHeight = 0

onMounted(() => {
  listHeight = listRef.value?.getBoundingClientRect().height ?? 0
  listRef.value!.style.height = `${listHeight}px`
})

watch(show, () => {
  listRef.value!.style.height = show.value ? `${listHeight}px` : '0px'
})

function toggle() {
  show.value = !show.value
}
</script>

<template>
  <section class="p-6">
    <strong class="group/heading uppercase mb-2 flex justify-between cursor-pointer select-none" @click="toggle">
      {{ props.label }}
      <DownIcon class="size-4 opacity-65 group-hover/heading:opacity-100 transition-transform" :class="{ '-rotate-180': show }" />
    </strong>

    <div
      ref="listRef"
      class="flex flex-col gap-0 -mx-3 transition-[height] overflow-hidden"
      @click="$emit('close-sidebar')"
    >
      <slot />
    </div>
  </section>
</template>
