<script setup lang="ts">
import useGlobalStore from '@/store/global'
import imgBaseUrl from '@/helpers/imgBaseUrl'
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Navigation, Autoplay } from 'swiper/modules';
import ArrowIcon from '@/assets/icons/slide-arrow.svg?component'

const modules = [Pagination, Navigation, Autoplay];

const global = useGlobalStore()
</script>

<template>
  <div class="home__banners group/hero relative">
    <swiper
      :modules="modules"
      :loop="true"
      :autoplay="{
        delay: 5000,
        disableOnInteraction: false,
      }"
      :pagination="{
        clickable: true,
        renderBullet: function (index, className) {
          return `<div class='${className} !bg-white !w-[30px] !h-[3px] !rounded-none'></div>`;
        },
      }"
      :navigation="{
        nextEl: '.hero-next-button',
        prevEl: '.hero-prev-button',
      }"
      class="!-mt-8 md:!mt-0 !-mx-4 md:!mx-0 md:rounded-custom-max overflow-hidden"
    >
      <swiper-slide v-for="banner in global.banners" :key="banner.image">
        <a :href="banner.action">
          <img :src="imgBaseUrl(banner.image)" class="home__banner [aspect-ratio:554/205] w-full overflow-hidden object-cover">
        </a>
      </swiper-slide>
    </swiper>

    <ArrowIcon
      class="hero-prev-button size-12 md:size-16 absolute -left-1 top-2/4 -translate-y-2/4 cursor-pointer z-20 drop-shadow opacity-0 transition-opacity group-hover/hero:opacity-100"
    />
    <ArrowIcon
      class="hero-next-button -scale-100 size-12 md:size-16 absolute -right-1 top-2/4 -translate-y-2/4 cursor-pointer z-20 drop-shadow opacity-0 transition-opacity group-hover/hero:opacity-100"
    />
  </div>
</template>
