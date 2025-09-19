<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import useGlobalStore from "@/store/global";
import cn from "@/helpers/classnames";
import { trans } from "laravel-vue-i18n";
import { formatCurrency } from "../helpers/formatters";
import CloseIcon from "@/assets/icons/close.svg?component";
import telegramIcon from "@/assets/icons/telegram.svg?url";
import afiliadoIcon from "@/assets/icons/afiliado.svg?url";
import supportIcon from "@/assets/icons/support.svg?url";
import SportsIcon from "@/assets/icons/soccer-ball.svg?component";
import Button from "@/components/Button.vue";
import Boxing from "@/assets/icons/boxing.svg?component";
import SidebarSection from "@/components/SidebarSection.vue";
import ExternalLink from "@/components/ExternalLink.vue";
import SidebarLink from "@/components/SidebarLink.vue";

const global = useGlobalStore();

const matchMedia = window.matchMedia("(max-width: 768px)");
const isMobile = computed(() => matchMedia.matches);
const topGames = ref<any[]>([]);
const buttons = computed(
    () =>
        global.sidebar_links.find((group) => group.group_name === "buttons")
            ?.links
);
const linksGroups = computed(() =>
    global.sidebar_links.filter((group) => group.group_name !== "buttons")
);

onMounted(function () {
    global.showSidebar = !isMobile.value;

    document.getElementById("sidebar")!.classList.add("md:visible");

    function checkHomeDataIsSetted() {
        if ("homeDataCache" in window) {
            // console.log('homeDataCache in window', 'homeDataCache' in window, window.homeDataCache.top_games)
            topGames.value = (window.homeDataCache as any).top_games;
        } else {
            setTimeout(checkHomeDataIsSetted, 1000);
        }
    }
    checkHomeDataIsSetted();
});

// toggle sidebar on window resize
matchMedia.addEventListener("change", (evt) => {
    global.showSidebar = !evt.matches;
});

const jivoEnabled = "jivo_api" in window;

function openJivo() {
    // @ts-ignore
    window.jivo_api?.open();
}

function closeInMobile() {
    if (isMobile.value) global.toggleSidebar();
}

const showMMA = ["feier-ex.bet", "www.feier-ex.bet"].includes(
    window.location.host
);
</script>

<template>
    <aside
        id="sidebar"
        :class="
            cn(
                'bg-surface border-r border-[#ffffff1a] w-screen md:w-[280px] absolute top-0 left-0 bottom-[60px] transition-[top] md:transition-[width] will-change-[width,top] md:relative md:h-full z-[150] md:z-[100] overflow-auto hide-scrollbar overscroll-contain shrink-0',
                {
                    '!top-0 md:!w-[280px]': global.showSidebar,
                    'top-full md:!top-0 md:!w-0 pointer-events-none':
                        !global.showSidebar,
                }
            )
        "
    >
        <div class="min-w-[280px]">
            <section
                class="p-6 pb-3 flex justify-between items-center md:hidden"
            >
                <RouterLink to="/" @click="closeInMobile">
                    <img
                        :src="global.logoURL"
                        class="h-auto max-h-[27px] max-w-[180px] block"
                        :alt="global.website_name"
                    />
                </RouterLink>

                <button
                    class="bg-white/10 rounded-full p-1.5 active:scale-90"
                    title="Close"
                    @click="global.toggleSidebar()"
                >
                    <CloseIcon class="size-5 block text-white" />
                </button>
            </section>

            <div class="divide-y divide-[#ffffff1a]">
                <section
                    class="sidebar-buttons p-6 space-y-3.5"
                    @click="closeInMobile"
                >
                    <ExternalLink
                        v-for="button in buttons"
                        :to="button.url"
                        class="block"
                    >
                        <Button
                            primary
                            class="sidebar-button btn-highlight w-full font-semibold text-[1.25rem] md:text-[0.94rem] !p-4 flex justify-between overflow-hidden hover:scale-105"
                            :style="{
                                backgroundColor: button.color,
                            }"
                        >
                            {{ button.name }}
                            <span class="scale-[1.5]">{{ button.icon }}</span>
                        </Button>
                    </ExternalLink>
                </section>

                <SidebarSection
                    :label="$t('front.sports')"
                    v-if="global.sports_enabled"
                    @close-sidebar="closeInMobile"
                >
                    <SidebarLink to="/sports">
                        <SportsIcon class="size-6" />
                        <span>{{ $t("front.sport-bets") }}</span>
                    </SidebarLink>

                    <SidebarLink
                        v-if="showMMA"
                        to="https://www.feier-exfight.club/"
                    >
                        <Boxing class="size-6" />
                        <span> MMA </span>
                    </SidebarLink>
                </SidebarSection>

                <SidebarSection
                    v-for="group in linksGroups"
                    :label="group.group_name"
                    @close-sidebar="closeInMobile"
                >
                    <SidebarLink v-for="link in group.links" :to="link.url">
                        <img
                            :src="'/sidebar-icons/' + link.icon + '.svg'"
                            class="icon size-6"
                        />
                        <span>{{ link.name }}</span>
                    </SidebarLink>
                </SidebarSection>

                <!-- <SidebarSection
                    :label="$t('front.popular')"
                    v-if="topGames.length >= 4"
                    @close-sidebar="closeInMobile"
                >
                    <SidebarLink
                        v-for="item in topGames"
                        :to="item.slug"
                        class="group"
                    >
                        <img
                            :src="item.image"
                            class="h-[28px] aspect-[166/193] object-cover rounded transition-transform group-hover:scale-[1.16]"
                        />
                        <span>{{ item.name }}</span>
                    </SidebarLink>
                </SidebarSection> -->

                <section class="p-6" @click="closeInMobile">
                    <a
                        href="https://t.me"
                        target="_blank"
                        class="flex items-center gap-3 py-3 font-medium cursor-pointer opacity-70 !no-underline hover:opacity-100"
                    >
                        <img :src="telegramIcon" class="icon size-6" />
                        <span>{{ $t("front.telegram") }}</span>
                    </a>
                    <RouterLink
                        to="/user/refers"
                        class="flex items-center gap-3 py-3 font-medium cursor-pointer opacity-70 !no-underline hover:opacity-100"
                    >
                        <img :src="afiliadoIcon" class="icon size-6" />
                        <span>{{ $t("front.become-affiliate") }}</span>
                    </RouterLink>
                    <a
                        v-if="jivoEnabled"
                        class="flex items-center gap-3 py-3 font-medium cursor-pointer opacity-70 !no-underline hover:opacity-100"
                        @click="openJivo"
                    >
                        <img :src="supportIcon" class="icon size-6" />
                        <span>{{ $t("front.live-suport") }}</span>
                    </a>
                    <!-- <a href="https://ajuda.flashgames.bet/" class="flex items-center gap-3 py-3 font-medium cursor-pointer opacity-70 !no-underline hover:opacity-100">
            <img :src="helpIcon" class="icon size-6">
            <span>Central de Ajuda</span>
          </a> -->
                    <RouterLink
                        to="/user/refers"
                        class="flex items-center gap-3 py-3 font-medium cursor-pointer opacity-70 !no-underline hover:opacity-100"
                    >
                        <img :src="afiliadoIcon" class="icon size-6" />
                        <span>{{ $t("front.invite-friend") }}</span>
                    </RouterLink>
                </section>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.btn-highlight::after {
    @apply bg-gradient-to-r from-transparent via-white to-transparent blur-[1.6px] [mix-blend-mode:overlay] h-[4px] absolute bottom-0 left-[20%] right-[20%] content-[''];
}
</style>
