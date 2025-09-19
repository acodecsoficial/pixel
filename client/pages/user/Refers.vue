<script setup lang="ts">
import { ref, computed, reactive, onMounted } from "vue";
import { formatCurrency } from "@/helpers/formatters";
import useAuthStore from "@/store/auth";
import useGlobalStore from "@/store/global";
import useModalStore from "@/store/modals";
import copy from "@/helpers/copyToClipboard";
import { storeToRefs } from "pinia";
import api from "@/services/api";
import RequiresLogin from "@/components/RequiresLogin.vue";
import Input from "@/components/Input.vue";
import Button from "@/components/Button.vue";
import clipboardIcon from "@/assets/icons/clipboard-black.svg?url";
import shareIcon from "@/assets/icons/share.svg?url";
import { trans } from "laravel-vue-i18n";

const apperance = useGlobalStore();
const modals = useModalStore();
const auth = useAuthStore();
const { user } = storeToRefs(auth);

const link = computed(() => {
    const baseUrl = typeof window !== "undefined" ? window.location.origin : "";
    return baseUrl + "?ref=" + user.value.affiliation_code;
});
const amountAvailable = computed(
    () => user.value.referRewards - user.value.collected
);
const showCopiedMsg = ref(false);

function copyLink() {
    (document.getElementById("my-link-input") as HTMLInputElement).select();

    copy(link.value);

    showCopiedMsg.value = true;
    setTimeout(() => {
        showCopiedMsg.value = false;
    }, 3000);

    if ("fbq" in window) fbq("track", "CopyAffiliationCode");
}

function shareLink() {
    window.navigator.share({
        title: trans("refer-link-title"),
        url: link.value,
    });

    if ("fbq" in window) fbq("track", "CopyAffiliationCode");
}

const partnersLink =
    "https://partners." + window.location.host.replace("www.", "");
</script>

<template>
    <RequiresLogin>
        <div
            class="bg-white/10 rounded-lg p-6 w-full max-w-[720px] mx-auto mt-8 mb-16"
        >
            <strong class="text-primary text-2xl font-bold block mb-4">{{
                $t("front.refer-heading")
            }}</strong>

            <p class="font-medium my-4">
                {{
                    $t("front.refer-subheading", {
                        value: formatCurrency(apperance.cpa),
                    })
                }}
            </p>
            <p
                class="text-amber-500 font-medium my-4"
                v-if="apperance.cpa_min >= 0"
            >
                {{
                    $t("front.refer-first-deposit", {
                        value: formatCurrency(apperance.cpa_min),
                    })
                }}
            </p>

            <hr class="border-t border-t-gray-500/70 my-6" />

            <Input
                name="my-link"
                id="my-link-input"
                readonly
                class="text-gray-200/90"
                :modelValue="link"
            >
                <template #before>
                    <label
                        for="my-link-input"
                        class="block font-medium text-base mb-1"
                        >{{ $t("front.refer-link") }}:</label
                    >
                </template>
            </Input>

            <div class="flex items-center gap-4 mt-5">
                <Button
                    class="bg-primary text-black text-lg w-full py-2.5"
                    @click="copyLink"
                >
                    <img :src="clipboardIcon" class="icon size-5" />
                    {{
                        showCopiedMsg
                            ? $t("front.link-copied")
                            : $t("front.copy-link")
                    }}
                </Button>

                <Button class="text-lg w-full py-2.5" @click="shareLink">
                    <img :src="shareIcon" class="icon size-5" />
                    {{ $t("front.share") }}
                </Button>
            </div>

            <hr class="border-t border-t-gray-500/70 my-6" />

            <div class="my-4">
                <strong class="text-xl font-semibold block mb-4">{{
                    $t("front.affiliate-panel")
                }}</strong>

                <a :href="partnersLink" v-if="user.affiliate" target="_blank">
                    <Button primary class="text-lg font-medium w-full py-3">{{
                        $t("front.access-affiliate-panel")
                    }}</Button>
                </a>
                <a :href="partnersLink" v-else target="_blank">
                    <Button primary class="text-lg font-medium w-full py-3">{{
                        $t("front.request-panel-access")
                    }}</Button>
                </a>
            </div>
        </div>
    </RequiresLogin>
</template>
