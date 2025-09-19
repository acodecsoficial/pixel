<script setup lang="ts">
import { formatCurrency } from '@/helpers/formatters';
import useModalStore from '@/store/modals'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import walletDepositIcon from '@/assets/icons/wallet-deposit.svg?url'
import checkIcon from '@/assets/icons/check.svg?url'
import clockIcon from '@/assets/icons/clock.svg?url'
import Button from '@/components/Button.vue'

const props = defineProps<{
  data: Record<any, any>[]
}>()

const modals = useModalStore()
</script>

<template>
  <DataTable
    :value="props.data"
    paginator
    :rows="10"
    :rowsPerPageOptions="[10, 20, 30, 50]"
    table-class="w-full"
  >
    <Column field="status" header="">
      <template #body="slotProps">
        <div class="rounded-full w-fit p-1.5" :class="{
          'bg-amber-400/90': slotProps.data.status === 'pending',
          'bg-emerald-400': slotProps.data.status === 'paid',
        }">
          <img :src="walletDepositIcon" class="icon size-5 min-w-5">
        </div>
      </template>
    </Column>
    <Column field="worth" :header="$t('front.amount')">
      <template #body="slotProps">
        {{ formatCurrency(slotProps.data.worth) }}
      </template>
    </Column>
    <Column field="status" :header="$t('front.status')">
      <template #body="slotProps">
        <span
          class="p-0.5 px-1.5 rounded-md text-gray-950 font-medium text-sm flex items-center gap-1 w-fit text-nowrap"
          :class="{
            'bg-amber-400/90': slotProps.data.status === 'pending',
            'bg-emerald-400': slotProps.data.status === 'paid',
          }"
        >
          <img :src="(slotProps.data.status === 'paid' ? checkIcon : clockIcon)" class="size-3.5 min-w-3.5">
          {{ slotProps.data.status === 'paid' ? $t('front.approved') : $t('front.pending') }}
        </span>
      </template>
    </Column>
    <Column field="rollover" :header="$t('front.with-bonus')">
      <template #body="slotProps">
        {{ slotProps.data.rollover == 1 ? $t('front.yes') : $t('front.no') }}
      </template>
    </Column>
    <Column field="date" :header="$t('front.date')">
      <template #body="slotProps">
        {{ new Date(slotProps.data.created_at).toLocaleString() }}
      </template>
    </Column>

    <template #empty>
      <span class="text-amber-500 block text-center py-10">
        {{ $t('front.no-transactions') }} <br/> {{ $t('front.make-first-deposit')}}
        <Button primary class="text-xl mx-auto mt-5 flex-[1_1_auto]" @click="modals.openDepositModal()">
          <img :src="walletDepositIcon" class="icon size-4">
          {{ $t('front.deposit-verb') }}
        </Button>
      </span>
    </template>

    <!-- <template #footer> In total there are {{ props.data ? props.data.length : 0 }} products. </template> -->

  </DataTable>
</template>

<style>
.p-datatable {
  @apply bg-transparent !text-white border-none mt-1;
}
.p-datatable thead,
.p-datatable thead tr,
.p-datatable thead th {
  @apply text-white bg-transparent font-normal;
}

.p-datatable thead th {
  @apply py-4
}

.p-datatable-header, .p-datatable-footer {
  @apply bg-transparent border-none text-white;
}

.p-datatable-tbody > tr > td {
  @apply bg-transparent text-white py-3.5;
}

.p-datatable-tbody > tr {
  @apply bg-transparent text-white font-normal border-t border-t-gray-500;
}

.p-paginator-bottom {
  @apply mt-2;
}

.p-paginator-bottom,
.p-paginator,
.p-paginator-rpp-options,
.p-paginator-rpp-options > .p-dropdown-label {
  @apply bg-transparent !text-white;
}
</style>
