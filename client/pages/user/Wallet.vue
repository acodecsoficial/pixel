<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { formatCurrency } from '@/helpers/formatters'
import useAuthStore from '@/store/auth'
import useModalStore from '@/store/modals'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import api from '@/services/api'
import cn from '@/helpers/classnames'
import RequiresLogin from '@/components/RequiresLogin.vue'
import WalletBalanceCard from '@/components/WalletBalanceCard.vue'
import WalletDepositsTable from '@/components/WalletDepositsTable.vue'
import WalletWithdrawsTable from '@/components/WalletWithdrawsTable.vue'
import Button from '@/components/Button.vue'
import depositIcon from '@/assets/icons/wallet-deposit.svg?url'
import withdrawIcon from '@/assets/icons/wallet-withdraw.svg?url'
import PixIcon from '@/assets/icons/pix-icon.svg?component'

const auth = useAuthStore()
const modals = useModalStore()

const deposits = ref<any[]>([])
const withdraws = ref<any[]>([])
const isLoading = ref(true)

onMounted(() => {
  loadCharges()
  loadWithdraws()
})

function loadCharges() {
  api.get('/wallet/deposits')
    .then(res => {
      deposits.value = res.data.data
    })
    .catch(err => {})
    .finally(() => {
      isLoading.value = false
    })
}

function loadWithdraws() {
  api.get('/wallet/withdrawals')
    .then(res => {
      withdraws.value = res.data.data
    })
    .catch(err => {})
    .finally(() => {
      isLoading.value = false
    })
}

const defaultTab = new URLSearchParams(location.search).get('tab') === 'withdrawals' ? 1 : 0
</script>

<template>
  <RequiresLogin>
    <div class="grid gap-5 grid-cols-1 md:grid-cols-2">

      <WalletBalanceCard
        :heading="$t('front.balance')"
        :balance="auth.wallet?.balance ?? 0"
      >
        <template #icon>
          <svg class="size-7" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M48 127.1L448 128C448.4 128 448.9 128 449.3 128C460.5 128.3 470.9 131.6 480 136.9V136.6C499.1 147.6 512 168.3 512 192V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V80C0 106.5 21.49 128 48 128L48 127.1zM416 336C433.7 336 448 321.7 448 304C448 286.3 433.7 272 416 272C398.3 272 384 286.3 384 304C384 321.7 398.3 336 416 336z" fill="white"></path><path d="M0 80C0 53.49 21.49 32 48 32H432C458.5 32 480 53.49 480 80V136.6C470.6 131.1 459.7 128 448 128L48 128C21.49 128 0 106.5 0 80V80z" fill="white" opacity="0.4"></path></svg>
        </template>

        <template #buttons>
          <Button primary class="text-xl flex-[1_1_auto]" @click="modals.openDepositModal()">
            <img :src="depositIcon" class="icon size-4">
            {{ $t('front.deposit-verb') }}
            <span class="rounded-full bg-amber-400 px-1.5 py-0 text-xxs font-medium leading-6 absolute -top-2 -right-2 flex items-center gap-0.5 scale-90">
              <PixIcon class="size-3" />
              {{ $t('front.pix') }}
            </span>
          </Button>
          <Button
            class="text-xl bg-white text-stone-950 flex-[1_1_auto]"
            @click="modals.openWithdrawModal('normal')"
            :disabled="auth.wallet?.balance <= 0"
          >
            <img :src="withdrawIcon" class="icon size-4">
            {{ $t('front.withdrawal-verb') }}
          </Button>
        </template>
      </WalletBalanceCard>

      <WalletBalanceCard
        v-if="auth.wallet?.bonus > 0 || auth.user.user_demo"
        :heading="$t('front.bonus')"
        :balance="auth.wallet?.bonus ?? 0"
        :class="{
          '-order-1': auth.user.user_demo
        }"
      >
        <template #after_balance>
          <div class="text-gray-400 text-sm mt-4">* {{ $t('front.bonus-alert') }}</div>
        </template>

        <template #buttons>
          <RouterLink to="/casino/pg-soft" class="flex-[1_1_auto]">
            <Button primary class="text-lg font-medium flex-[1_1_auto] w-full">{{ $t('front.bonus-games') }}</Button>
          </RouterLink>
          <Button class="text-sm font-normal flex-[1_1_auto]" @click="modals.showBonusRulesModal = true">{{ $t('front.bonus-questions') }}</Button>
        </template>
      </WalletBalanceCard>
    </div>


    <div class="bg-white/10 rounded-custom-max p-6 w-full mt-10">
      <strong class="text-2xl text-primary font-semibold block mb-5">{{ $t('front.transactions') }}</strong>

      <TabGroup :defaultIndex="defaultTab">
        <TabList as="div" class="border-b border-b-gray-400">
          <Tab as="template" v-slot="{ selected }">
            <div
              :class="cn('tab inline-block rounded-t-lg border border-b-0 px-4 py-2.5 -mb-[1px] cursor-pointer', {
                'bg-background !border-gray-400 font-medium': selected,
                'border-transparent': !selected
              })"
            >{{ $t('front.deposits') }}</div>
          </Tab>
          <Tab as="template" v-slot="{ selected }">
            <div
              :class="cn('tab inline-block rounded-t-lg border border-b-0 px-4 py-2.5 -mb-[1px] cursor-pointer', {
                'bg-background !border-gray-400 font-medium': selected,
                'border-transparent': !selected
              })"
            >{{ $t('front.withdrawals')}}</div>
          </Tab>
        </TabList>

        <TabPanels>
          <TabPanel class="overflow-auto w-full">
            <WalletDepositsTable :data="deposits" />
          </TabPanel>

          <TabPanel>
            <WalletWithdrawsTable :data="withdraws" />
          </TabPanel>
        </TabPanels>

      </TabGroup>
    </div>

  </RequiresLogin>
</template>
