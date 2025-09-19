<script setup lang="ts">
import { titleCase, upperOnlyFirstLetter } from '@/helpers/capitilize'
import imgBaseUrl from '@/helpers/imgBaseUrl'
import PlayIcon from '@/assets/icons/play.svg?component'

const props = defineProps<{
  name: string,
  slug: string,
  image: string,
  provider: string,
  isSkeleton?: boolean
}>()

const providers: Record<string, string> = {
  'PG Soft': 'PGSoft',
  'Pragmatic Play': 'Pragmatic Play',
  'pragmatic slot': 'Pragmatic',
}
const provider = providers[props.provider] ?? upperOnlyFirstLetter(props.provider)
const link = props.slug.startsWith('/') ? props.slug : `/${props.slug}`
</script>

<template>
  <RouterLink
    :to="link"
    class="list__item block group/item bg-[#444849] rounded-custom-max relative aspect-[166/193] cursor-pointer overflow-hidden snap-start active:scale-95 focus-visible:ring-1 focus-visible:ring-white transition-transform duration-[.5s]"
    :class="{
      'bg-white/10': props.isSkeleton,
    }"
  >
    <template v-if="!props.isSkeleton">
      <img
        :src="imgBaseUrl(image)"
        loading="lazy"
        alt=""
        class="object-cover absolute top-0 left-0 h-full w-full group-hover/item:scale-[1.16] pointer-events-none transition-transform duration-[.5s]"
      />

      <div
        class="drop-shadow-sm absolute bottom-0 left-0 right-0 p-3 pt-10 bg-gradient-to-t from-black/60 to-transparent"
        v-if="props.name"
      >
        <div class="font-semibold text-sm leading-3 md:text-[.9rem] truncate">{{ titleCase(props.name) }}</div>
        <span class="block text-sm opacity-90 mt-0.5">{{ provider }}</span>
      </div>

      <PlayIcon class="play-icon" />
    </template>
  </RouterLink>
</template>

<style scoped>
  .play-icon {
    @apply absolute size-12 p-2 border-[3px] border-white rounded-full top-2/4 left-2/4 -translate-x-2/4 -translate-y-2/4 drop-shadow-lg transition-opacity duration-[.5s] opacity-0 group-hover/item:opacity-100 pointer-events-none;
  }
</style>
