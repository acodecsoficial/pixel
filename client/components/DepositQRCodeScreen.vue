<script setup lang="ts">
import { ref, onUnmounted, watch } from 'vue'
import useGlobalStore from '@/store/global'
import { formatCurrency } from '@/helpers/formatters'
import copy from '@/helpers/copyToClipboard'
import cursorIcon from '@/assets/icons/cursor-click.svg?url'
import Button from '@/components/Button.vue'
import type { DepositData } from '@/components/Modals/DepositModal.vue'
import Spinner from '@/components/Spinner.vue'

const props = defineProps<{
  data: DepositData,
}>()

const global = useGlobalStore()

const showCopiedMsg = ref(false)

const totalTime = 300; // 5 minutes in seconds
const timeLeft = ref(totalTime);
let timerInterval: NodeJS.Timeout;

function startTimer() {
  timeLeft.value = totalTime

  const updateTimer = () => {
    if (timeLeft.value > 0) {
      timeLeft.value--;
    } else {
      clearInterval(timerInterval);
    }
  };

  timerInterval = setInterval(updateTimer, 1000);
}

watch(() => props.data.qrCode, () => {
  if (props.data.qrCode) {
    startTimer()
  }
})

onUnmounted(() => {
  clearInterval(timerInterval);
})

function copyPixKey() {
  (document.getElementById('pixKeyInput') as HTMLInputElement).select();
  copy(props.data.pixCode);
  showCopiedMsg.value = true;
  setTimeout(() => {
    showCopiedMsg.value = false;
  }, 3000)
}


</script>

<template>
  <div id="qr-code-screen">
    <h2 class="text-2xl font-bold text-primary">{{ $t('front.deposit-verb') }}</h2>

    <div class="text-center text-lg/6 my-6" v-html="props.data.qrCode ? $t('front.deposit.qr-code-instructions') : $t('front.deposit.generating-qr-code')"></div>

    <div class="relative">
      <img
        :src="props.data.qrCode"
        class="size-60 rounded-md mx-auto mb-8"
        :class="{ 'invisible': !props.data.qrCode }"
      >
      <span v-if="!props.data.qrCode" class="absolute top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4">
        <img v-if="global.favicon" :src="global.favicon" class="size-12 animate-[pulsar_1s_linear_infinite]">
        <Spinner v-else />
      </span>
    </div>

    <div
      class="border border-dashed border-gray-400 rounded-custom-max p-7 flex flex-col gap-3 items-center relative"
      :class="{
        'border-rose-400': timeLeft <= 0
      }"
      @click="copyPixKey"
    >
      <strong class="font-bold text-4xl text-primary mb-1">{{ formatCurrency(props.data.amount) }}</strong>
      <input
        id="pixKeyInput"
        type="text"
        readonly
        :value="props.data.pixCode"
        :placeholder="$t('front.deposit.generating-qr-code')"
        class="bg-white/10 rounded-custom-max py-4 px-4 w-full text-sm !outline-none"
      >
      <Button class="w-full bg-primary/10 text-primary text-lg" :disabled="!props.data.pixCode">
        {{ showCopiedMsg ? $t('front.deposit.pix-copied') : $t('front.deposit.copy-pix') }}
      </Button>
      <img :src="cursorIcon" class="p-1 size-10 cursor-pointer absolute top-3 right-3 opacity-60" :title="$t('front.deposit.copy-pix')">
    </div>

    <template v-if="props.data.qrCode">
      <div class="mt-5">
        <div v-if="timeLeft > 0">
          <strong class="text-amber-500">{{ $t('front.deposit.qr-code-expires-in') }}:</strong><br>
          <strong class="text-xl mb-1 block">{{ `${(Math.floor(timeLeft / 60)).toString().padStart(2, '0')}:${(timeLeft % 60).toString().padStart(2, '0')}` }}</strong>
          <div class="w-full bg-white/10 h-1">
            <div class="bg-primary h-1" :style="{ width: `${(timeLeft / totalTime) * 100}%` }" />
          </div>
        </div>
        <div v-else class="text-rose-500 text-2xl text-center font-bold">{{ $t('front.deposit.qr-code-expired') }}</div>
      </div>

      <template v-if="timeLeft > 0">
        <div class="text-gray-300 text-center mt-12 mb-4" v-if="timeLeft > 0">{{ $t('front.deposit.waiting-payment') }}</div>
        <Button class="mx-auto text-gray-300 bg-white/5" @click="$emit('check-payment-status')">{{ $t('front.deposit.already-paid') }}</Button>
      </template>

      <Button v-else class="mx-auto text-gray-300 bg-white/5 mt-6" @click="$emit('close')">{{ $t('front.close') }}</Button>
    </template>
  </div>
</template>
