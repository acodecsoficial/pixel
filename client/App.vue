<script setup lang="ts">
import { onMounted } from 'vue'
import useGlobalStore from './store/global'
import useAuthStore from './store/auth'
import Modals from '@/components/Modals/Modals.vue'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
import Sidebar from '@/components/Sidebar.vue'
// @ts-ignore
import BottomNavigation from '@/components/BottomNavigation.vue'
import LoadingScreen from '@/components/LoadingScreen.vue'
import ToastNotification from '@/components/ToastNotification.vue'
// @ts-ignore
import BackToTop from '@/components/BackToTop.vue'

const global = useGlobalStore()
const auth = useAuthStore()

onMounted(() => {
  auth.init()
  global.init()
})
</script>

<template>
  <LoadingScreen>
    <div class="h-dvh w-screen flex flex-col relative">
      <Header />

      <div class="flex items-start flex-grow overflow-hidden">
        <Sidebar />

        <main class="flex-[1_1_auto] h-full overflow-auto overscroll-contain">
          <div class="container pt-8">
            <RouterView />
          </div>

          <Footer />
        </main>
      </div>

      <BottomNavigation />
    </div>
  </LoadingScreen>

  <Modals />
  <ToastNotification />
  <BackToTop />

</template>
