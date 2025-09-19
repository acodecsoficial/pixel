<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  // add @ts-ignore if using TypeScript
  ...RouterLink.props,
  inactiveClass: String,
})

const isExternalLink = computed(() => {
  return typeof props.to === 'string' && props.to.startsWith('http') && !props.to.startsWith(window.location.origin)
})

const siteOrigin = window.location.origin
</script>

<template>
  <a
    v-if="isExternalLink"
    v-bind="$attrs"
    :href="to"
    target="_blank"
    :class="props.class"
  >
    <slot />
  </a>

  <router-link
    v-else
    v-bind="$props"
    :to="$props.to.replace(siteOrigin, '')"
    custom
    v-slot="{ href, navigate }"
  >
    <a
      v-bind="$attrs"
      :href="href"
      @click="navigate"
      :class="props.class"
    >
      <slot />
    </a>
  </router-link>
</template>
