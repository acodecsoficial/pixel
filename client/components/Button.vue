<script setup lang="ts">
import useGlobalStore from '@/store/global'
import cn from '../helpers/classnames'

const props = defineProps<{
  class?: string
  primary?: boolean,
  outlined?: boolean,
}>()

const global = useGlobalStore()
</script>

<template>
  <button
    :class="cn('button bg-[#fdffff1a] text-white px-3 sm:px-4 py-2 rounded-custom relative flex justify-center items-center gap-2.5 !no-underline [text-shadow:0px_1px_1px_rgb(0_0_0/20%)] hover:opacity-90 active:scale-95 transition-transform disabled:opacity-30 disabled:pointer-events-none select-none', {
      'button-primary  bg-primary bg-gradient-to-b from-black/15 to-white/15 text-primary-contrast': props.primary?? false,
      'button-outlined bg-transparent border-none': props.outlined,
    }, props.class)"
  >
    <slot />
  </button>
</template>
