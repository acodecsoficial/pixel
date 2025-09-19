<script setup lang="ts">
import { formatCurrency } from "@/helpers/formatters";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup";
import Row from "primevue/row";
import withdrawIcon from "@/assets/icons/wallet-withdraw.svg?url";
import checkIcon from "@/assets/icons/check.svg?url";
import clockIcon from "@/assets/icons/clock.svg?url";
import closeIcon from "@/assets/icons/close-black.svg?url";

const props = defineProps<{
    data: Record<any, any>[];
}>();
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
                <div
                    class="rounded-full w-fit p-1.5"
                    :class="{
                        'bg-amber-400/90': slotProps.data.status === 'pending',
                        'bg-emerald-400': slotProps.data.status === 'approved',
                        'bg-rose-500': slotProps.data.status === 'denied',
                    }"
                >
                    <img :src="withdrawIcon" class="icon size-5 min-w-5" />
                </div>
            </template>
        </Column>
        <Column field="worth" :header="$t('front.amount')">
            <template #body="slotProps">
                {{ formatCurrency(slotProps.data.amount) }}
            </template>
        </Column>
        <Column field="status" :header="$t('front.status')">
            <template #body="slotProps">
                <span
                    class="py-0.5 pl-2 pr-6 lg:pr-2 rounded-md text-gray-950 font-medium text-sm flex items-center gap-1 w-fit text-nowrap"
                    :class="{
                        'bg-amber-400/90': slotProps.data.status === 'pending',
                        'bg-emerald-400': slotProps.data.status === 'approved',
                        'bg-rose-500': slotProps.data.status === 'denied',
                    }"
                >
                    <img
                        :src="({
            'approved': checkIcon,
            'denied': closeIcon,
            'pending': clockIcon,
          })[slotProps.data.status as string]"
                        class="size-3.5"
                    />
                    {{
                        {
                            approved: $t("front.approved"),
                            denied: $t("front.denied"),
                            pending: $t("front.pending"),
                        }[slotProps.data.status as string]
                    }}
                </span>
            </template>
        </Column>
        <Column field="description" :header="$t('front.details')">
            <template #body="slotProps">
                {{ slotProps.data.description ?? "---" }}
            </template>
        </Column>
        <Column field="date" :header="$t('front.date')">
            <template #body="slotProps">
                {{ new Date(slotProps.data.created_at).toLocaleString() }}
            </template>
        </Column>

        <template #empty>
            <span class="text-amber-500 block text-center py-10">
                {{ $t("front.no-transactions") }}
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
    @apply py-4;
}

.p-datatable-header,
.p-datatable-footer {
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
