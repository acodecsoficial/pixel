<script setup lang="ts">
import { computed } from "vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import { formatCurrency } from "@/helpers/formatters";
import { defaultAvatar } from "@/helpers/constants";
import { useRoute } from "vue-router";
import useAuthStore from "@/store/auth";
import useModalStore from "@/store/modals";
import Button from "@/components/Button.vue";
import ProfileIcon from "@/assets/icons/profile.svg?component";
import AccountIcon from "@/assets/icons/account.svg?component";
import WalletIcon from "@/assets/icons/wallet.svg?component";
import WalletDepositIcon from "@/assets/icons/wallet-deposit.svg?component";
import WalletWithdrawIcon from "@/assets/icons/wallet-withdraw.svg?component";
import AffiliateIcon from "@/assets/icons/painel-afiliado.svg?component";
import KeyIcon from "@/assets/icons/key.svg?component";
import ChatIcon from "@/assets/icons/chat.svg?component";
import LogoutIcon from "@/assets/icons/logout.svg?component";

const auth = useAuthStore();
const modals = useModalStore();

const route = useRoute();
const isUserGaming = computed(() => route.name === "game");
</script>

<template>
    <Menu as="div" class="relative h-9">
        <MenuButton>
            <Button class="!p-0 bg-transparent relative">
                <img
                    :src="auth.user?.avatar ?? defaultAvatar"
                    class="block size-9 rounded-full"
                />
                <!-- <CircularProgress :progress="40" :style="{'--stroke-margin': '-3px'}" /> -->
            </Button>
        </MenuButton>

        <transition
            enter-active-class="transition duration-100 ease-out origin-top-right"
            enter-from-class="transform scale-90 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out origin-top-right"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-90 opacity-0"
        >
            <MenuItems
                class="account-dropdown bg-background border border-[#fdffff1a] text-white rounded-custom-max absolute top-[calc(100%+10px)] -right-1 w-[250px] py-2 shadow-2xl"
            >
                <div
                    class="max-h-[calc(100vh-76px)] overflow-auto hide-scrollbar"
                >
                    <MenuItem as="div" class="py-2 px-6" v-slot="{ close }">
                        <RouterLink
                            to="/user/account"
                            v-slot="{ href, navigate }"
                            custom
                        >
                            <a
                                :href="href"
                                @click.prevent="
                                    navigate();
                                    close();
                                "
                            >
                                <img
                                    :src="auth.user?.avatar ?? defaultAvatar"
                                    class="block size-12 rounded-full mx-auto mb-2"
                                />
                                <div
                                    class="account-dropdown-email text-center font-medium mb-3"
                                >
                                    {{ auth.user?.email }}
                                </div>

                                <Button class="w-full">
                                    <AccountIcon class="size-5" />
                                    <span>{{ $t("front.my-account") }}</span>
                                </Button>
                            </a>
                        </RouterLink>
                    </MenuItem>

                    <MenuItem
                        as="hr"
                        class="account-dropdown-divider border-t border-white/10 my-3 pointer-events-none"
                    />

                    <MenuItem class="py-1 px-6" v-slot="{ close }">
                        <div
                            v-if="isUserGaming"
                            class="text-lg font-medium text-gray-500 -mt-1"
                        >
                            ({{ $t("front.gaming") }})
                        </div>
                        <RouterLink
                            v-else
                            to="/user/wallet"
                            class="account-dropdown-wallet flex gap-3 items-center justify-between text-base hover:!no-underline"
                        >
                            <div
                                class="account-dropdown-balance flex-[1_1_auto]"
                            >
                                <div class="text-sm">
                                    {{ $t("front.balance") }}:
                                </div>
                                <strong>{{
                                    formatCurrency(auth.wallet?.balance)
                                }}</strong>
                            </div>
                            <div
                                class="account-dropdown-bonus flex-[1_1_auto]"
                                v-if="auth.wallet?.bonus > 0"
                            >
                                <div class="text-sm">
                                    {{ $t("front.bonus") }}:
                                </div>
                                <strong>{{
                                    formatCurrency(auth.wallet?.bonus)
                                }}</strong>
                            </div>
                        </RouterLink>
                    </MenuItem>

                    <MenuItem
                        as="hr"
                        class="account-dropdown-divider border-t border-white/10 my-3 pointer-events-none"
                    />

                    <MenuItem v-slot="{ close }">
                        <RouterLink
                            to="/user/wallet"
                            v-slot="{ href, navigate }"
                            custom
                        >
                            <a
                                class="account-dropdown-item"
                                :href="href"
                                @click.prevent="
                                    navigate();
                                    close();
                                "
                            >
                                <WalletIcon class="size-5" />
                                <span>{{ $t("front.wallet") }}</span>
                            </a>
                        </RouterLink>
                    </MenuItem>
                    <MenuItem v-slot="{ close }">
                        <div
                            class="account-dropdown-item"
                            @click.prevent="
                                modals.openDepositModal();
                                close();
                            "
                        >
                            <WalletDepositIcon class="size-5" />
                            <span>{{ $t("front.deposit-verb") }}</span>
                        </div>
                    </MenuItem>
                    <MenuItem v-slot="{ close }">
                        <div
                            class="account-dropdown-item"
                            @click.prevent="
                                modals.openWithdrawModal('normal');
                                close();
                            "
                            :class="{
                                'pointer-events-none opacity-75 bg-white/5':
                                    auth.wallet?.balance <= 0,
                            }"
                        >
                            <WalletWithdrawIcon class="size-5" />
                            <span>{{ $t("front.withdrawal-verb") }}</span>
                        </div>
                    </MenuItem>
                    <MenuItem v-slot="{ close }">
                        <RouterLink
                            to="/user/refers"
                            v-slot="{ href, navigate }"
                            custom
                        >
                            <a
                                class="account-dropdown-item"
                                :href="href"
                                @click.prevent="
                                    navigate();
                                    close();
                                "
                            >
                                <AffiliateIcon class="size-5" />
                                <span>{{ $t("front.affiliate-panel") }}</span>
                            </a>
                        </RouterLink>
                    </MenuItem>
                    <MenuItem v-slot="{ close }">
                        <RouterLink
                            to="/user/account/change-password"
                            v-slot="{ href, navigate }"
                            custom
                        >
                            <a
                                class="account-dropdown-item"
                                :href="href"
                                @click.prevent="
                                    navigate();
                                    close();
                                "
                            >
                                <KeyIcon class="size-5" />
                                <span>{{ $t("front.change-pass") }}</span>
                            </a>
                        </RouterLink>
                    </MenuItem>

                    <!--
          <MenuItem v-slot="{ close }">
            <RouterLink to="/user/refers" v-slot="{ href, navigate }" custom>
              <a class="account-dropdown-item" :href="href" @click.prevent="navigate();close()">
                <ChatIcon class="size-5" />
                <span>{{ $t('front.live-suport') }}</span>
              </a>
            </RouterLink>
          </MenuItem>-->

                    <MenuItem
                        as="hr"
                        class="account-dropdown-divider border-t border-white/10 my-3 pointer-events-none"
                    />

                    <MenuItem v-slot="{ close }">
                        <div
                            class="account-dropdown-item"
                            @click="
                                auth.logout();
                                close();
                            "
                        >
                            <LogoutIcon class="size-5" />
                            <span>{{ $t("front.logout") }}</span>
                        </div>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<style scoped>
.account-dropdown::before {
    @apply bg-inherit border-t border-l border-inherit absolute rounded-[2px] size-3 -top-[4.5px] right-[14px] rotate-45;
    content: "";
}

.account-dropdown-item {
    @apply hover:bg-primary/25 flex gap-3 items-center text-white font-normal py-2.5 px-6 cursor-pointer !no-underline;
}

.account-dropdown-button {
    @apply bg-white/5 rounded-lg hover:bg-primary/25 flex gap-3 items-center text-white font-normal py-2.5 px-6 cursor-pointer !no-underline;
}
</style>
