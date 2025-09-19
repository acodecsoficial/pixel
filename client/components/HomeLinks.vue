<script setup lang="ts">
import deckIcon from '@/assets/icons/deck.svg?component'
import dicesIcon from '@/assets/icons/dices.svg?component'
import aviadorIcon from '@/assets/icons/aviador.svg?component'
import fortuneDragonIcon from '@/assets/icons/aviador.svg?component'
import motoGrauIcon from '@/assets/icons/moto-grau.svg?component'
import videoIcon from '@/assets/icons/video.svg?component'
import targetIcon from '@/assets/icons/target.svg?component'
import baccaratIcon from '@/assets/icons/baccarat.svg?component'
import cashManiaIcon from '@/assets/icons/cash-mania.svg?component'
import ninjaIcon from '@/assets/icons/ninja.svg?component'

const items = [
  { text: 'Cassino', icon: deckIcon, href: '/casino/category/all' },
  { text: 'Cassino Ao Vivo', icon: dicesIcon, href: '/casino/category/jogos-pg-soft' },
  { text: 'Aviador', icon: aviadorIcon, href: '/casino/spribe/15000' },
  { text: 'Fortune Tiger', icon: fortuneDragonIcon, href: '/casino/pgsoft/126' },
  { text: 'Fortune Dragon', icon: fortuneDragonIcon, href: '/casino/pgsoft/1695365' },
  { text: 'Moto Grau', icon: motoGrauIcon, href: '/casino/hypertech/motograu' },
  { text: 'Wall Street Bull', icon: videoIcon, href: '/casino/hypertech/wall-street' },
  { text: 'Lightning Roulette', icon: targetIcon, href: '/casino/evolution/25549' },
  { text: 'Bac Bo', icon: dicesIcon, href: '/casino/evolution/25559' },
  { text: 'Baccarat', icon: baccaratIcon, href: '/casino/evolution/25278' },
  { text: 'Cash Mania', icon: cashManiaIcon, href: '/casino/pgsoft/1682240' },
  { text: 'Wild Ape', icon: ninjaIcon, href: '/casino/pgsoft/1508783' },
]
</script>

<template>
  <div class="flex gap-4 px-4 items-start mt-8 mb-4 -mx-4 md:mx-0 overflow-auto hide-scrollbar">
    <RouterLink
      v-for="item in items"
      :to="item.href"
      class="flex flex-col gap-2 items-center cursor-pointer !no-underline hover:opacity-80 w-[86px] active:scale-[.93] transition-transform"
    >
      <div class="bg-primary/10 rounded-full size-16 flex items-center justify-center">
        <component :is="item.icon" class="icon *:fill-primary size-8" />
      </div>
      <strong class="text-[0.95rem] font-normal text-center leading-5 block">{{ item.text }}</strong>
    </RouterLink>
  </div>
</template>
