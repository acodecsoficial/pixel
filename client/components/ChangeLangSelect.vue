<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { getActiveLanguage, loadLanguageAsync } from "laravel-vue-i18n";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
// @ts-ignore
import { findFlagUrlByCountryName } from "country-flags-svg";
import DownIcon from "@/assets/icons/down.svg?component";

const currentLang = ref(getActiveLanguage());

watch(currentLang, () => {
    loadLanguageAsync(currentLang.value);
    localStorage.setItem("lang", currentLang.value);
});

const langs = [
    {
        code: "en",
        name: "English",
        flag: findFlagUrlByCountryName("United States"),
    },
    {
        code: "pt_BR",
        name: "Português",
        flag: findFlagUrlByCountryName("Brazil"),
    },
    { code: "es", name: "Español", flag: findFlagUrlByCountryName("Spain") },
    {
        code: "ko",
        name: "Korean",
        flag: findFlagUrlByCountryName("South Korea"),
    },
    { code: "mn", name: "Mongol", flag: findFlagUrlByCountryName("Mongolia") },
    { code: "hi", name: "Hindi", flag: findFlagUrlByCountryName("India") },
];
const selectedLang = computed(() =>
    langs.find((lang) => lang.code === currentLang.value)
);
</script>

<template>
    <Listbox
        v-model="currentLang"
        as="div"
        class="relative shrink-0 mx-auto w-fit"
        di
    >
        <ListboxButton
            class="border border-gray-600/60 bg-white/5 rounded-custom-max py-2 px-3 flex items-center gap-2 text-sm"
            tabindex="-1"
        >
            <img :src="selectedLang?.flag" height="18" width="21" />
            {{ selectedLang?.name }}
            <DownIcon class="size-3 ml-1" />
        </ListboxButton>

        <ListboxOptions
            class="bg-[#424344] rounded-md py-1 min-w-[120px] max-h-[220px] absolute left-0 bottom-full mb-1 z-10 overflow-auto"
        >
            <ListboxOption
                v-for="lang in langs"
                :key="lang.code"
                :value="lang.code"
                class="hover:bg-white/5 px-4 py-3 cursor-pointer flex items-center gap-2"
            >
                <img :src="lang.flag" height="18" width="21" />
                {{ lang.name }}
            </ListboxOption>
        </ListboxOptions>
    </Listbox>
</template>
