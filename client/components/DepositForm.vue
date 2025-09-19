<script setup lang="ts">
import { ref, reactive, computed, watch } from "vue";
import { formatCurrency, cpfMask, currencyMask } from "@/helpers/formatters";
import { isValidCPF } from "@/helpers/validations";
import type { DepositData } from "@/components/Modals/DepositModal.vue";
import useGlobalStore from "@/store/global";
import useAuthStore from "@/store/auth";
import api from "@/services/api";
import z from "zod";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Mousewheel } from "swiper/modules";
import Button from "@/components/Button.vue";
import Input from "@/components/Input.vue";
import CheckIcon from "@/assets/icons/check.svg?component";
import DocumentIcon from "@/assets/icons/document.svg?component";
import QrCodeIcon from "@/assets/icons/qrcode.svg?component";
import FireIcon from "@/assets/icons/fire.svg?component";
import MinusIcon from "@/assets/icons/minus.svg?component";
import PlusIcon from "@/assets/icons/plus.svg?component";
// @ts-ignore
import img from "@/assets/images/aprovacao-imediata.webp?url";
import { trans } from "laravel-vue-i18n";

const global = useGlobalStore();
const auth = useAuthStore();

const props = defineProps<{
    data: DepositData;
}>();

const emit = defineEmits<{
    (e: "submit", payload: void): void;
}>();

const couponDetais = reactive({
    code: "",
    type: "" as "real" | "bonus",
    value: 0,
    min_deposit_value: 0,
});
const showCouponInput = ref(false);
const showBonusCheckbox = computed(
    () => global.deposit.show_bonus_banner && auth.user?.deposit_sum <= 0
);
const error = ref<string | null>(null);

const depositSchema = z.object({
    amount: z.number(),
    document: z.string().refine(isValidCPF, trans("front.deposit.cpf-invalid")),
    coupon: z.string().optional(),
});

function handleSubmit() {
    const validation = depositSchema.safeParse(props.data);

    if (!validation.success) {
        return (error.value = validation.error.issues[0]?.message);
    }

    if (props.data.amount < global.deposit.min_amount) {
        return (error.value = trans("front.deposit.min-deposit", {
            value: formatCurrency(global.deposit.min_amount),
        }));
    }

    if (
        couponDetais.code &&
        props.data.amount < couponDetais.min_deposit_value
    ) {
        return (error.value = trans("front.deposit.min-coupon-amount", {
            value: formatCurrency(couponDetais.min_deposit_value),
        }));
    }

    error.value = null;

    // Enviar os dados pro componente pai processar o depósito
    emit("submit");
}

watch(
    () => couponDetais.code,
    () => {
        props.data.coupon = "";
        Object.assign(couponDetais, {
            type: "",
            value: 0,
            min_deposit_value: 0,
        });
    }
);

function validateCoupon() {
    api.get("/coupons/" + encodeURIComponent(couponDetais.code))
        .then((res) => res.data)
        .then((res) => {
            if (props.data.amount < res.minimum_deposit_value) {
                return window.notify(
                    trans("front.deposit.min-coupon-amount", {
                        value: formatCurrency(res.minimum_deposit_value),
                    }),
                    "error"
                );
            }

            // Cupom válido
            couponDetais.code = res.coupon;
            couponDetais.min_deposit_value = res.minimum_deposit_value;
            couponDetais.value = res.value;
            couponDetais.type = res.type;
            props.data.coupon = res.coupon;
            window.notify(trans("front.deposit.coupon-applied"), "success");
        })
        .catch((err) => {
            const msg =
                err.response?.data?.message ??
                trans("front.deposit.coupon-invalid");
            return window.notify(msg, "error");
        });
}

const predefinedValues = [10, 30, 50, 100, 200, 500, 1000, 2000, 5000].filter(
    (number) => number >= global?.deposit?.min_amount
);
</script>

<template>
    <div id="form-screen">
        <div class="-m-10 mb-2 relative">
            <img :src="img" class="w-full sm:rounded-t-lg" />
            <span
                class="absolute h-8 bottom-0 left-0 right-0 bg-gradient-to-t from-background to-transparent"
            />
        </div>

        <h2 class="text-2xl font-bold text-primary">
            {{ $t("front.deposit-verb") }}
        </h2>

        <div class="card bg-white/5 rounded-custom-max py-5 px-6 mt-6">
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <div class="flex justify-between mb-1">
                        <label
                            for="amount-input"
                            class="font-medium text-sm grow required"
                            >{{ $t("front.deposit.amount") }}:</label
                        >
                        <span
                            class="font-medium text-sm text-primary cursor-pointer"
                            v-if="!showCouponInput"
                            @click="showCouponInput = true"
                            >{{ $t("front.deposit.has-coupon") }}</span
                        >
                    </div>

                    <Input
                        type="text"
                        inputmode="numeric"
                        name="amount"
                        id="amount-input"
                        required
                        :modelValue="currencyMask(props.data.amount)"
                        @update:modelValue="
                            (newValue = '0,00') =>
                                (props.data.amount = Number(
                                    newValue.replace(/[\.\,]/gi, '')
                                ))
                        "
                        class="[&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        placeholder="0,00"
                    >
                        <template #before_input>
                            <strong class="font-semibold">$</strong>
                        </template>

                        <template #after_input>
                            <div
                                class="border border-white/5 bg-white/5 divide-x-2 divide-white/5 rounded-custom flex items-center"
                            >
                                <PlusIcon
                                    class="h-6 py-1 px-1.5 cursor-pointer select-none active:bg-white/5"
                                    @click="props.data.amount += 10"
                                />
                                <MinusIcon
                                    class="h-6 py-1 px-1.5 cursor-pointer select-none active:bg-white/5"
                                    @click="
                                        props.data.amount = Math.max(
                                            props.data.amount - 10,
                                            0
                                        )
                                    "
                                />
                            </div>
                        </template>
                    </Input>

                    <swiper
                        :freeMode="true"
                        :pagination="false"
                        :spaceBetween="8"
                        :slidesPerView="'auto'"
                        :mousewheel="true"
                        :modules="[Mousewheel]"
                        :resistanceRatio="0.54"
                        class="mb-2 -mt-0.5 pt-3 pb-1 pl-[2px]"
                        wrapperClass="swiper-wrapper"
                    >
                        <template v-for="(amount, index) in predefinedValues">
                            <swiper-slide
                                class="bg-primary/10 text-primary text-base font-semibold w-fit rounded-custom p-3 py-2 cursor-pointer relative hover:bg-primary/20"
                                :class="{
                                    'ring-2 ring-yellow-400': index === 1,
                                }"
                                @click="props.data.amount = amount"
                            >
                                <span
                                    v-if="index === 1"
                                    class="bg-yellow-400 rounded-md absolute -top-0.5 left-2/4 -translate-y-2/4 -translate-x-2/4 text-xxs text-black leading-4 px-1 uppercase flex gap-1 items-center text-nowrap"
                                    ><FireIcon class="size-[0.64rem] inline" />
                                    {{ $t("front.hot") }}</span
                                >

                                {{ formatCurrency(amount) }}
                            </swiper-slide>
                        </template>
                    </swiper>
                    <div
                        class="flex gap-2 overflow-auto hide-scrollbar mb-3"
                    ></div>
                </div>

                <div class="my-4" v-if="showCouponInput">
                    <label
                        for="coupon-input"
                        class="block font-medium text-sm mb-1"
                        >{{ $t("front.deposit.coupon") }}:</label
                    >
                    <Input
                        type="text"
                        name="coupon"
                        id="coupon-input"
                        :placeholder="$t('front.deposit.coupon-code')"
                        :modelValue="couponDetais.code"
                        @update:modelValue="
                            (newValue) =>
                                (couponDetais.code = newValue.toUpperCase())
                        "
                        @keydown.enter.stop.prevent="validateCoupon"
                    >
                        <template #before_input>
                            <svg
                                class="size-4 shrink-0"
                                viewBox="0 0 576 512"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M448 128C465.7 128 480 142.3 480 160V352C480 369.7 465.7 384 448 384H128C110.3 384 96 369.7 96 352V160C96 142.3 110.3 128 128 128H448zM448 160H128V352H448V160z"
                                    fill="currentColor"
                                ></path>
                                <path
                                    d="M128 160H448V352H128V160zM512 64C547.3 64 576 92.65 576 128V208C549.5 208 528 229.5 528 256C528 282.5 549.5 304 576 304V384C576 419.3 547.3 448 512 448H64C28.65 448 0 419.3 0 384V304C26.51 304 48 282.5 48 256C48 229.5 26.51 208 0 208V128C0 92.65 28.65 64 64 64H512zM96 352C96 369.7 110.3 384 128 384H448C465.7 384 480 369.7 480 352V160C480 142.3 465.7 128 448 128H128C110.3 128 96 142.3 96 160V352z"
                                    fill="currentColor"
                                    opacity="0.4"
                                ></path>
                            </svg>
                        </template>

                        <template #after_input>
                            <Button
                                type="button"
                                class="bg-primary text-sm uppercase py-0.5 !px-2 shink-0"
                                v-if="couponDetais.code.length > 0"
                                @click="validateCoupon"
                            >
                                <CheckIcon
                                    class="size-5"
                                    v-if="
                                        props.data.coupon === couponDetais.code
                                    "
                                />
                                <span v-else>{{
                                    $t("front.deposit.validate")
                                }}</span>
                            </Button>
                        </template>
                    </Input>

                    <div
                        id="coupon-infos"
                        class="space-y-1 text-sm"
                        v-if="couponDetais.value"
                    >
                        <div class="flex items-center gap-2">
                            <div>{{ $t("front.deposit.deposit-amount") }}</div>
                            <span
                                class="grow border-t border-dashed border-t-gray-500"
                            />
                            <div>{{ formatCurrency(props.data.amount) }}</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div>
                                {{ $t("front.deposit.coupon-value") }}
                                {{
                                    couponDetais.type == "bonus"
                                        ? $t("front.deposit.bonus")
                                        : ""
                                }}
                            </div>
                            <span
                                class="grow border-t border-dashed border-t-gray-500"
                            />
                            <div>{{ formatCurrency(couponDetais.value) }}</div>
                        </div>
                    </div>
                </div>

                <div class="my-4">
                    <label
                        for="document-input"
                        class="block font-medium text-sm mb-1 required"
                        >{{ $t("front.cpf") }}</label
                    >
                    <Input
                        type="text"
                        name="document"
                        id="document-input"
                        required
                        maxlength="14"
                        :placeholder="$t('front.type-cpf')"
                        :mask="['###.###.###-##', '##.###.###/####-##']"
                        :modelValue="cpfMask(props.data.document)"
                        @update:modelValue="
                            (newValue) => (props.data.document = newValue)
                        "
                    >
                        <template #before_input>
                            <DocumentIcon class="size-4 opacity-50" />
                        </template>
                    </Input>
                </div>

                <label
                    class="text-lg leading-5 flex gap-2.5 items-center my-4"
                    v-if="showBonusCheckbox"
                >
                    <input
                        type="checkbox"
                        class="accent-primary scale-110"
                        v-model="props.data.accept_bonus"
                    />
                    {{ $t("front.deposit.want-bonus") }}
                </label>

                <p
                    class="bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-3.5 my-3"
                    v-if="error"
                >
                    {{ error }}
                </p>

                <Button
                    primary
                    class="w-full py-3 mt-6 text-xl font-semibold shadow-md shadow-primary/40"
                >
                    <QrCodeIcon class="size-5" />
                    {{ $t("front.deposit.generate-qr-code") }}
                </Button>
            </form>
        </div>
    </div>
</template>

<style scoped>
.required::after {
    @apply text-[#ff6969] text-sm content-['*'] ml-0.5;
}
</style>
