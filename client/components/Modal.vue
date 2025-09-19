<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import cn from '@/helpers/classnames'
import CloseIcon from '@/assets/icons/close.svg?component'

const props = defineProps({
  onClose: { type: Function, required: true },
  closeOnBackdropClick: { type: Boolean, default: true },
  modalWidth: { type: Number },
  modalClass: { type: String },
})

const wrapperElemRef = ref<HTMLElement | null>(null)

function close() {
  props.onClose?.()
}

let fnHandler: any;
onMounted(() => {
  // Start opening modal animation
  requestAnimationFrame(() => {
    wrapperElemRef.value?.classList.add('!opacity-100')
    wrapperElemRef.value?.querySelector('.modal')!.classList.remove('scale-95')
  })

  fnHandler = function (evt: KeyboardEvent) {
    if (evt.key === 'Escape') close()
  }
  window.addEventListener('keyup', fnHandler)
})

onUnmounted(() => {
  window.removeEventListener('keyup', fnHandler)
})
</script>

<template>
  <div class="modal__wrapper bg-black/70 fixed top-0 left-0 bottom-0 right-0 z-[500] py-12 flex justify-center items-center opacity-0 transition-opacity duration-100 overflow-auto backdrop-blur-sm" ref="wrapperElemRef">
    <div
      v-if="props.closeOnBackdropClick"
      class="modal__backdrop fixed top-0 left-0 right-0 bottom-0 z-[0]"
      @click="close()"
    />

    <div
      role="dialog"
      :class="cn('modal bg-background absolute inset-0 sm:relative sm:inset-[unset] sm:rounded-custom-max m-auto z-10 scale-95 transition-transform', props.modalClass)"
    >
      <CloseIcon @click="close()" class="bg-background rounded-full size-9 p-2 absolute z-10 top-3 right-3 sm:-top-3 sm:-right-3 text-white cursor-pointer active:scale-75 transition-transform" />

      <div
        class="max-h-[100vh] sm:max-h-none max-w-[100vw] mx-auto px-10 py-9 overflow-auto overscroll-contain"
        :style="{ width: (props.modalWidth ?? 520) + 'px' }"
      >
        <slot />
      </div>
    </div>
  </div>
</template>
