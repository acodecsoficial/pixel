<script setup lang="ts">
import { ref } from 'vue'
import CloseIcon from '@/assets/icons/close.svg?component'

const notifications = ref<Notification[]>([])

interface Notification {
  msg: string,
  type: 'success' | 'error' | 'alert' | 'info',
  timeout: number,
  id: number
}

if (typeof window !== 'undefined') {
  window.notify = function (msg, type = 'success', timeout = 4000) {
    const id = Date.now()
    notifications.value = notifications.value.concat({
      msg,
      type,
      timeout,
      id,
    })

    setTimeout(() => {
      close(id)
    }, timeout)
  }
}

function close(toastId: number) {
  notifications.value = notifications.value.filter(item => item.id !== toastId)
}

declare global {
  interface Window {
    notify(message: string, type?: Notification['type'], timeout?: number): void
  }
}
</script>

<template>
  <TransitionGroup
    enter-active-class="transition duration-[280ms] ease-out origin-bottom"
    enter-from-class="transform scale-90 translate-y-full opacity-0"
    enter-to-class="transform scale-100 opacity-100"
    leave-active-class="transition duration-100 ease-out"
    leave-from-class="transform opacity-100"
    leave-to-class="transform opacity-0"
  >
    <div
      v-for="notification in notifications"
      :key="notification.msg"
      class="toast rounded-md text-white px-4 py-3.5 md:w-[420px] flex justify-between items-center absolute z-[9999999] left-3 md:left-[unset] right-3 bottom-[86px] md:bottom-3.5 [body:has(.modal)_&]:bottom-3.5"
      :class="{
        'bg-emerald-500': notification.type === 'success',
        'bg-rose-500': notification.type === 'error',
        'bg-amber-500 !text-black': notification.type === 'alert',
        'bg-sky-500': notification.type === 'info',
      }"
    >
      <span class="grow">{{ notification.msg }}</span>
      <CloseIcon class="size-4 text-white opacity-80 cursor-pointer drop-shadow hover:opacity-60 active:scale-90 transition-all" @click="close(notification.id)" />
    </div>
  </TransitionGroup>
</template>

<style scoped>
/* .toast {
  animation: slide-up 260ms ease-out;
}

@keyframes slide-up {
  from {
    transform: translateY(100%) scale(0.9);
    opacity: 0;
  }
  to {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
} */
</style>
