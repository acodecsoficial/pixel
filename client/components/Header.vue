<script setup lang="ts">
import { computed } from "vue";
import useAuthStore from "@/store/auth";
import useModalStore from "@/store/modals";
import useGlobalStore from "@/store/global";
import { formatCurrency } from "@/helpers/formatters";
import { useRoute } from "vue-router";
import { trans } from "laravel-vue-i18n";
import AccountDropdown from "@/components/AccountDropdown.vue";
import Button from "@/components/Button.vue";
// @ts-ignore
import TopMessage from "@/components/TopMessage.vue";
import SearchBox from "@/components/SearchBox.vue";
import DeckIcon from "@/assets/icons/deck.svg?component";
import SportsIcon from "@/assets/icons/soccer-ball.svg?component";
import PixIcon from "@/assets/icons/pix-icon.svg?component";
import GiftIcon from "@/assets/icons/gift.svg?component";
import LockIcon from "@/assets/icons/lock.svg?component";
import MenuIcon from "@/assets/icons/hamburguer-menu.svg?component";
import ReloadIcon from "@/assets/icons/reload.svg?component";
import Boxing from "@/assets/icons/boxing.svg?component";
import LoginIcon from "@/assets/icons/login.svg?component";
import SearchIcon from "@/assets/icons/search.svg?component";
import CloseIcon from "@/assets/icons/close.svg?component";
import DepositIcon from "@/assets/icons/wallet-deposit.svg?component";

const route = useRoute();

const auth = useAuthStore();
const modals = useModalStore();
const global = useGlobalStore();
const isSportsSelected = computed(() => route.path.includes("/sports"));
const isUserGaming = computed(() => route.name === "game");

const handleSportLinkClick = (event: any) => {
    if (!global.sports_enabled) {
        event.preventDefault();
        return window.notify(trans("front.sports-blocked"), "info");
    }
};

const showMMA = ["feier-ex.bet", "www.feier-ex.bet"].includes(
    window.location.host
);
</script>

<template>
    <div class="relative top-0 left-0 w-full z-[120] flex-[0_0_auto]">
        <TopMessage v-if="global.showTopMessage" />

        <header
            class="header bg-surface border-b border-[#fdffff1a] h-[64px] flex items-center relative md:pl-[280px]"
        >
            <div
                :class="{
                    'ml-[1.93rem]': showMMA,
                }"
                class="absolute left-0 top-0 w-[280px] h-[64px] justify-center hidden md:flex"
            >
                <RouterLink
                    to="/"
                    class="uppercase font-semibold text-sm text-center py-4 px-3.5 opacity-60 border-b-4 border-transparent flex justify-center items-center gap-2 !no-underline"
                    :class="{
                        '!border-primary !opacity-100': !isSportsSelected,
                    }"
                >
                    <DeckIcon class="size-5" />
                    {{ $t("front.casino") }}
                </RouterLink>
                <RouterLink
                    class="uppercase font-semibold text-sm text-center py-4 px-3.5 opacity-60 border-b-4 border-transparent flex justify-center items-center gap-2 !no-underline"
                    :class="{
                        '!border-primary !opacity-100': isSportsSelected,
                        'cursor-default': !global.sports_enabled,
                        'hover:opacity-100': global.sports_enabled,
                    }"
                    :to="global.sports_enabled ? '/sports' : '#'"
                    @click="handleSportLinkClick"
                >
                    <component
                        :is="global.sports_enabled ? SportsIcon : LockIcon"
                        class="size-5"
                    />
                    {{ $t("front.sports") }}
                </RouterLink>

                <a
                    target="_blank"
                    v-if="showMMA"
                    href="https://www.feier-exfight.club/"
                    class="uppercase font-semibold text-sm text-center py-4 px-3.5 opacity-60 border-b-4 border-transparent flex justify-center items-center gap-2 !no-underline hover:opacity-100"
                >
                    <Boxing class="size-5" />
                    MMA
                </a>
            </div>

            <div class="container px-2 sm:px-4 grow flex items-center gap-3">
                <div class="grow flex items-center gap-2">
                    <Button
                        :class="{
                            'sm:ml-16': showMMA,
                        }"
                        class="header-menu-icon cursor-pointer !p-1.5 -ml-1.5 hidden md:block"
                        outlined
                        @click="global.toggleSidebar()"
                    >
                        <MenuIcon class="icon size-7" />
                    </Button>
                    <RouterLink to="/" class="inline-block">
                        <img
                            :src="global.logoURL"
                            class="header-logo max-h-[40px] h-auto block"
                            :alt="global.website_name"
                        />
                    </RouterLink>
                </div>

                <div
                    class="flex items-center gap-2.5 md:gap-3 uw:gap-3.5"
                    v-if="!auth.isLoading"
                >
                    <SearchIcon
                        class="size-6 p-0.5 mr-1 cursor-pointer transition-transform hover:scale-110 active:scale-90"
                        @click="global.toggleHeaderSearchBox()"
                    />

                    <template v-if="auth.isAuthenticated">
                        <Button
                            primary
                            class="header-deposit-button uppercase font-medium py-1.5 !px-3 gap-1.5 shadow-md shadow-primary/40 hover:scale-105 hidden md:flex"
                            @click="modals.openDepositModal()"
                        >
                            <DepositIcon class="size-4 hidden sm:block" />
                            {{ $t("front.deposit-verb") }}
                            <span
                                class="rounded-full bg-amber-400 px-1.5 py-0.5 text-xxs font-medium absolute -top-2 -right-2 flex items-center gap-0.5 scale-90"
                            >
                                <PixIcon class="size-3" />
                                {{ $t("front.pix") }}
                            </span>
                        </Button>

                        <div class="flex items-center gap-1 sm:gap-2 ml-1">
                            <ReloadIcon
                                class="header-reload-wallet size-4 cursor-pointer hidden sm:block"
                                :class="{
                                    'animate-spin': auth.isWalletLoading,
                                }"
                                @click="auth.fetchWallet()"
                            />

                            <div
                                v-if="isUserGaming"
                                class="text-lg font-medium text-gray-400 -mt-1"
                            >
                                ({{ $t("front.gaming") }})
                            </div>
                            <template v-else>
                                <RouterLink
                                    to="/user/wallet"
                                    v-if="auth.user.user_demo"
                                    class="header-balances !no-underline text-right"
                                >
                                    <div
                                        class="header-bonus text-primary font-bold text-base xs:text-lg italic leading-4"
                                    >
                                        {{
                                            formatCurrency(
                                                auth.wallet?.bonus ?? 0
                                            )
                                        }}
                                    </div>
                                    <div
                                        class="header-balance text-sm leading-4 opacity-40"
                                        v-if="auth.wallet?.balance > 0"
                                    >
                                        {{
                                            formatCurrency(
                                                auth.wallet?.balance ?? 0
                                            )
                                        }}
                                    </div>
                                </RouterLink>
                                <RouterLink
                                    to="/user/wallet"
                                    v-else
                                    class="!no-underline text-right"
                                >
                                    <div
                                        class="header-balance text-primary font-bold text-base xs:text-lg italic leading-4"
                                    >
                                        {{
                                            formatCurrency(
                                                auth.wallet?.balance ?? 0
                                            )
                                        }}
                                    </div>
                                    <div
                                        class="header-bonus text-sm leading-4 opacity-40"
                                        v-if="auth.wallet?.bonus > 0"
                                    >
                                        {{
                                            formatCurrency(
                                                auth.wallet?.bonus ?? 0
                                            )
                                        }}
                                    </div>
                                </RouterLink>
                            </template>
                        </div>

                        <AccountDropdown />
                    </template>

                    <template v-else>
                        <Button
                            primary
                            class="header-register-button uppercase font-normal md:font-medium py-1.5 md:py-2 shadow-md shadow-primary/60"
                            @click="modals.openRegisterModal()"
                        >
                            <span
                                class="rounded-full bg-amber-400 px-1.5 py-0.5 text-xxs font-medium absolute -top-2 -right-2 flex items-center gap-0.5 scale-90"
                                v-if="
                                    global.deposit.show_bonus_banner &&
                                    global.bonus_percent > 0
                                "
                            >
                                <GiftIcon class="size-3 text-rose-700" />
                                {{ global.bonus_percent }}%
                            </span>
                            {{ $t("front.register") }}
                        </Button>
                        <Button
                            class="header-login-button uppercase font-normal py-1.5 md:py-2"
                            @click="modals.openLoginModal()"
                        >
                            <LoginIcon class="size-5" />
                            {{ $t("front.enter") }}
                        </Button>
                    </template>
                </div>
            </div>
        </header>
    </div>

    <div
        class="bg-surface border-b border-[#fdffff1a] absolute left-0 right-0 z-[110] transition-[top]"
        :class="{
            'top-[-100px]': !global.showHeaderSearchBox,
            'top-[64px]': global.showHeaderSearchBox && !global.showTopMessage,
            'top-[105px]': global.showHeaderSearchBox && global.showTopMessage,
            'md:left-[280px]': global.showSidebar,
        }"
    >
        <div class="container flex items-center gap-4">
            <div class="grow">
                <SearchBox
                    class="!my-6"
                    @onForceClose="global.toggleHeaderSearchBox()"
                />
            </div>
            <CloseIcon
                class="size-6 text-neutral-200 cursor-pointer hover:opacity-80 active:scale-90"
                @click="global.toggleHeaderSearchBox()"
            />
        </div>
    </div>
</template>
