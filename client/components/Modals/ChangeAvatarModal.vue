<script setup lang="ts">
import { ref } from 'vue'
import useModalStore from '@/store/modals';
import useAuthStore from '@/store/auth'
import api from '@/services/api'
import { trans } from 'laravel-vue-i18n'
import { defaultAvatar } from '@/helpers/constants'

const modals = useModalStore()
const auth = useAuthStore()

const selectedAvatar = ref(auth.user?.avatar || defaultAvatar)
const isLoading = ref(false)

const avatars = [
  'https://ik.imagekit.io/xgaming/tr:w-250/coruja-sortuda.jpeg?updatedAt=1724263416214',
  'https://ik.imagekit.io/xgaming/tr:w-250/raposa-cyborg.jpeg?updatedAt=1724263401804',
  'https://ik.imagekit.io/xgaming/tr:w-250/cavalo-gambler.jpeg?updatedAt=1724263463092',
  'https://ik.imagekit.io/xgaming/tr:w-250/golfinho-ludopata.jpeg?updatedAt=1724267251871',
  'https://ik.imagekit.io/xgaming/tr:w-250/tigrinho.jpeg?updatedAt=1724266751457',
  'https://ik.imagekit.io/xgaming/tr:w-250/guaxinim.jpeg?updatedAt=1724266996066',
  'https://ik.imagekit.io/xgaming/tr:w-250/coelho.jpeg?updatedAt=1724266277200',
  'https://ik.imagekit.io/xgaming/tr:w-250/macaquinho.jpeg?updatedAt=1724266543807',
  'https://ik.imagekit.io/xgaming/tr:w-250/viajante-do-tempo.jpeg?updatedAt=1724263878353',
  'https://ik.imagekit.io/xgaming/tr:w-250/camponesa-viciada.jpeg?updatedAt=1724264866253',
  'https://ik.imagekit.io/xgaming/tr:w-250/principe-dos-dragoes.jpeg?updatedAt=1724264257610',
  'https://ik.imagekit.io/xgaming/tr:w-250/mae-dos-dragoes.jpeg?updatedAt=1724264286135',
  'https://ik.imagekit.io/xgaming/tr:w-250/123456789.jpeg?updatedAt=1724274284680',
  'https://ik.imagekit.io/xgaming/tr:w-250/garoto-gamer.jpeg?updatedAt=1724265559924',
  'https://ik.imagekit.io/xgaming/tr:w-250/garota-gamer.jpeg?updatedAt=1724267127218',
  defaultAvatar,
]

function save() {
  isLoading.value = true

  api.patch(`/user`, {
    avatar: selectedAvatar.value,
  })
    .then(res => {
      auth.user.avatar = selectedAvatar.value

      window.notify(trans('front.account.saved'));
      close()
    })
    .catch(err => {
      window.notify(trans('front.account.not-saved'), 'error');
    })
    .finally(() => {
      isLoading.value = false
    })
}

function close() {
  modals.showPickAvatarModal = false;
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="460"
  >
    <h2 class="text-2xl text-center font-semibold">
      {{ $t('front.account.change-avatar') }}
    </h2>

    <div class="grid grid-cols-4 gap-5 w-full mt-6 mb-8">
      <img
        v-for="avatar in avatars"
        :src="avatar"
        class="rounded-full block cursor-pointer transition-transform hover:scale-110 active:scale-100"
        :class="{
          'ring-2 ring-primary ring-offset-4 ring-offset-surface': avatar === selectedAvatar,
        }"
        @click="selectedAvatar = avatar"
      />
    </div>

    <Button
      primary
      class="mx-auto min-w-[200px] text-lg font-medium"
      @click="save"
      :disabled="!selectedAvatar || isLoading"
    >
      <Spinner v-if="isLoading" class="size-7" />
      <span v-else>{{ $t('front.confirm') }}</span>
    </Button>
  </Modal>
</template>

<style scoped>
.rule-rows {
  @apply flex flex-col gap-3;
}

.rule-rows p {
  @apply font-medium;
}

.rule-rows span {
  @apply bg-primary/10 text-primary text-sm font-medium rounded-md p-1.5 py-0.5;
}

.rules-warning {
  @apply text-amber-500 border-t border-t-amber-500 pt-2.5 mt-6 text-sm flex flex-col gap-4;
}

.rules-warning p {
  @apply border-l border-l-amber-500 pl-3;
}
</style>
