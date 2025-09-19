<script setup lang="ts">
import { inject, computed, Ref } from 'vue'
import type { HomeData } from '@/interfaces'
import { formatCurrency } from '@/helpers/formatters'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Mousewheel } from 'swiper/modules';
// @ts-ignore
import trophyImg from '@/assets/images/trophy.webp?url'
import { trans } from 'laravel-vue-i18n';

const data = inject<Ref<HomeData>>('home-data')
const winners = computed(() => data?.value?.top_winners ?? [])

const modules = [Autoplay, Mousewheel]
</script>

<template>
  <section class="my-8 flex w-full" v-if="winners.length > 0">
    <div class="flex-none flex items-center justify-center flex-col sm:flex-row gap-1 sm:gap-3 z-10 relative rounded-l-custom-max bg-gradient-to-r from-primary/60 to-transparent py-2.5 pr-12 sm:pr-16 pl-3.5">
      <img :src="trophyImg" class="top-winners__trophy size-8 sm:size-16">
      <span class="drop-shadow-lg text-center sm:text-left">
        <span class="font-semibold text-[0.92rem] sm:text-xl">{{ $t('front.top-wins') }}</span>
        <span class="text-xs opacity-50 mt-1 hidden sm:block">{{  $t('front.24hours') }}</span>
      </span>
    </div>

    <swiper
      :modules="modules"
      :loop="true"
      :autoplay="{
        delay: 1000,
        disableOnInteraction: false,
        stopOnLastSlide: false,
      }"
      :mousewheel="{
        forceToAxis: true,
      }"
      :slidesPerView="'auto'"
      :spaceBetween="12"
      :pagination="false"
      class="top-winners -ml-14 pl-6 list-shadow"
    >
      <swiper-slide
        v-for="winner in winners"
        :key="winner.name"
        class="top-winners__item w-fit bg-white/5 rounded-custom-max p-3 flex items-center gap-2"
      >
        <img :src="winner.game_image" class="top-winners__game-img aspect-[166/193] w-[50px] sm:w-[70px] rounded-md object-cover">
        <div class="w-[100px] sm:w-[140px]">
          <div class="top-winners__name font-semibold truncate text-ellipsis">{{ winner.name }}</div>
          <div class="top-winners__game-name text-gray-400 truncate text-ellipsis">{{ winner.game_name }}</div>
          <div class="top-winners__amount text-yellow-500 font-bold">{{ formatCurrency(winner.amount) }}</div>
        </div>
      </swiper-slide>
    </swiper>
  </section>
</template>
