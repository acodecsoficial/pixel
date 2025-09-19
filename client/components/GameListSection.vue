<script setup lang="ts">
import { ref, onMounted } from 'vue'
import GameListItem from '@/components/GameListItem.vue'
import ChevronIcon from '@/assets/icons/chevron-right.svg?component'
import ArrowIcon from '@/assets/icons/slide-arrow.svg?component'

const props = defineProps<{
  name: string,
  more?: string,
  games?: any[],
  isSkeleton?: boolean,
  isTopGames?: boolean,
  showCount?: boolean,
}>()

const listRef = ref<HTMLDivElement | null>(null)
const showLeftArrow = ref(false)
const showRightArrow = ref(true)

onMounted(() => {
  const elemWidth = listRef.value!.getBoundingClientRect().width
  const listIsScrollable = listRef.value!.scrollWidth > elemWidth

  function onScroll() {
    const scrollLeft = listRef.value!.scrollLeft

    showLeftArrow.value = listRef.value!.scrollLeft > 10
    showRightArrow.value = scrollLeft + elemWidth <= listRef.value!.scrollWidth - 10 && listIsScrollable
  }
  listRef.value?.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
})

function slide(direction: 'right'|'left') {
  const scrollLeft = listRef.value?.scrollLeft!
  const elemWidth = listRef.value?.getBoundingClientRect().width!

  listRef.value?.scrollTo({
    left: (direction === 'right'
      ? scrollLeft + elemWidth
      : scrollLeft - elemWidth
    ),
    behavior: 'smooth'
  })
}
</script>

<template>
  <section
    class="my-8 group"
    :class="{ 'animate-pulse pointer-events-none': props.isSkeleton }"
  >
    <div class="flex justify-between items-center gap-2 mb-2.5">
      <strong
        class="section__title text-2xl font-semibold block"
        :class="{
          'text-transparent bg-white/10 rounded-md': props.isSkeleton
        }"
      >{{ props.name }}</strong>

      <span class="grow"></span>

      <span
        v-if="!!props.showCount"
        class="section__count text-gray-500 text-xs sm:text-sm leading-3 pb-[2px]"
        :class="{
          'text-transparent bg-white/10 rounded-2xl min-w-[60px]': props.isSkeleton
        }"
      >{{ $t('front.games-count', { count: props.games?.length+'' }) }}</span>

      <RouterLink
        :to="props.more"
        v-if="props.more"
        class="section_more bg-primary/20 text-primary text-xxs rounded-full px-2 py-0.5 pr-1 flex items-center gap-1 relative z-20 hover:no-underline"
      >
        {{ $t('front.section-more') }}
        <ChevronIcon class="size-3 text-primary" />
      </RouterLink>
    </div>

    <div
      class="list-shadow -mx-4 md:mx-0"
      :class="{ 'before:!opacity-0': !showLeftArrow, 'after:!opacity-0': !showRightArrow }"
    >
      <div class="section__list list flex gap-3 md:gap-5 px-4 py-0.5 md:px-0 flex-nowrap overflow-x-auto overflow-y-hidden scroll-smooth hide-scrollbar" ref="listRef">
        <div
          v-for="game, index in props.games"
          class="relative group/item"
          :class="{
            'pl-10': props.isTopGames === true
          }"
        >
          <span v-if="props.isTopGames === true" class="top-games__number text-[8.2rem]/[7.2rem] font-bold tracking-[-1.8rem] absolute -left-2 bottom-0 drop-shadow-[0_0_1px_rgb(var(--primary-color)/100%)] [-webkit-text-stroke:2px_rgb(var(--primary-color)/50%)] transition-all duration-[.5s] select-none text-primary/20 group-hover/item:text-primary/100 group-hover/item:-translate-x-2" :class="{
            '!-left-5': index>=9,
            '!left-0.5': index == 0
          }">{{ index+1 }}</span>

          <GameListItem
            :name="game.name"
            :slug="game.slug"
            :image="game.image"
            :provider="game.provider_name"
            class="min-w-[135px] md:min-w-[170px] uw:min-w-[220px]"
            :isSkeleton="props.isSkeleton ?? false"
          />
        </div>
      </div>

      <div class="opacity-0 transition-opacity group-hover:opacity-100">
        <ArrowIcon
          class="size-12 md:size-16 absolute left-0 top-2/4 -translate-y-2/4 cursor-pointer z-20"
          @click="slide('left')"
          :class="{ '!opacity-0 pointer-events-none': !showLeftArrow }"
        />
        <ArrowIcon
          class="size-12 md:size-16 absolute right-0 top-2/4 -translate-y-2/4 cursor-pointer z-20 -scale-100"
          @click="slide('right')"
          :class="{ '!opacity-0 pointer-events-none': !showRightArrow }"
        />
      </div>
    </div>

  </section>
</template>
