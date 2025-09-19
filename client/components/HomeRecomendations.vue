<script setup lang="ts">
import useGlobalStore from '@/store/global'
import GameListItem from '@/components/GameListItem.vue'
import imgBaseUrl from '@/helpers/imgBaseUrl'
import { trans } from 'laravel-vue-i18n';

const global = useGlobalStore()

function removeUrlOrigin(url: string) {
  const pattern = /^(https?:\/\/)?([^\/]+)/
  return url.replace(pattern, '')
}
</script>

<template>
  <section class="mt-6 mb-8">
    <strong class="section__title text-2xl font-semibold block mb-4">{{  $t('front.recomended') }}</strong>

    <div class="mobile-recomendations -mx-4 px-4 flex gap-3 flex-row flex-nowrap overflow-auto hide-scrollbar sm:hidden">
      <GameListItem
        v-for="item in global.mobile_action_btns"
        :image="imgBaseUrl(item.image)"
        :slug="removeUrlOrigin(item.action)"
        name=""
        provider=""
        class="min-w-[120px] md:min-w-[190px] md:hidden"
      />
    </div>

    <div class="desktop-recomendations gap-3.5 flex-row hidden sm:flex">
      <a
        v-for="item in global.desktop_action_btns"
        :href="removeUrlOrigin(item.action)"
        class="flex-[1_1_0px]"
      >
        <img :src="imgBaseUrl(item.image)" class="aspect-[362/103] object-contain object-center rounded-custom-max">
      </a>
    </div>
  </section>
</template>
