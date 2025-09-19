<script setup>
import ExternalLink from '@/components/ExternalLink.vue'
import cn from '@/helpers/classnames'
import { RouterLink } from 'vue-router'

const props = defineProps({
  // add @ts-ignore if using TypeScript
  ...RouterLink.props,
  inactiveClass: String,
})

const classes = cn('flex items-center gap-3 py-2.5 px-3.5 font-medium cursor-pointer opacity-70 !no-underline rounded-custom hover:opacity-100 hover:bg-white/10 active:scale-95 transition-all', props.class)
</script>

<template>
  <ExternalLink
    :to="props.to"
    v-bind="$attrs"
    :class="classes"
  >
    <slot />
  </ExternalLink>
</template>
