<script setup>
import { ref, onMounted } from 'vue'
import useGlobalStore from '@/store/global'
import UpIcon from '@/assets/icons/up.svg?component'

const global = useGlobalStore();
const show = ref(false);

function backToTop() {
  document.querySelector('main')?.scrollTo({ top: 0, behavior: 'smooth' });
}

onMounted(async () => {
  await new Promise(resolve => setTimeout(resolve, 1000))

  document.querySelector('main')?.addEventListener('scroll', function(evt) {
    show.value = evt.target.scrollTop > 400;
  }, { passive: true });
})
</script>

<template>
  <div
    v-if="show"
    @click="backToTop"
    class="bg-black/80 border border-white/25 rounded-full px-2.5 py-1 fixed left-2/4 -translate-x-2/4 bottom-4 mb-[72px] md:mb-0 z-[100] text-sm shadow-xl flex items-center gap-2 cursor-pointer opacity-80 hover:opacity-100"
    :class="{
      'md:ml-[140px]': global.showSidebar,
    }"
  >
    <UpIcon class="size-3.5" />
    {{ $t('front.scroll-top') }}
  </div>
</template>
