<script setup>
import pgSoftLogo from "@/assets/images/pgsoft-logo.svg?url";
import evolutionLogo from "@/assets/images/evolution-logo.svg?url";
import spribeLogo from "@/assets/images/spribe-logo.avif?url";
import pragmaticLogo from "@/assets/images/pragmaticplay-logo.svg?url";
import ezugiLogo from "@/assets/images/ezugi-logo.webp?url";
import hypetechLogo from "@/assets/images/HYPETECH-logo.png?url";
import ChevronIcon from "@/assets/icons/chevron-right.svg?component";

const providers = [
    { name: "PG Soft", slug: "/casino/PG%20Soft", image: pgSoftLogo },
    { name: "Evolution", slug: "/casino/Evolution", image: evolutionLogo },
    {
        name: "Pragmatic Play",
        slug: "/casino/pragmatic%20slot",
        image: pragmaticLogo,
    },
    { name: "Ezugi", slug: "/casino/Ezugi", image: ezugiLogo },
];
</script>

<template>
    <section id="providers-section" class="my-6">
        <div class="flex justify-between items-center gap-2 mb-2.5">
            <strong class="text-2xl font-semibold block">{{
                $t("front.providers")
            }}</strong>

            <!-- <RouterLink
        to="/"
        class="bg-primary/20 text-primary text-xxs rounded-full px-2 py-0.5 pr-1 flex items-center gap-1 relative z-20 hover:no-underline"
      >
        Ver todos
        <ChevronIcon class="size-3 text-primary" />
      </RouterLink> -->
        </div>

        <div class="list-shadow overflow-hidden -mx-4 md:mx-0">
            <div class="marquee">
                <template v-for="i in [1, 2, 3]">
                    <RouterLink
                        v-for="provider in providers"
                        :key="provider.slug"
                        :to="provider.slug"
                        class="w-[132px] h-[80px] block bg-white/5 shrink-0 rounded-custom-max relative lg:w-[166px] lg:h-[110px]"
                    >
                        <img
                            :src="provider.image"
                            class="p-4 object-contain object-center absolute top-0 left-0 w-full h-full pointer-events-none"
                        />
                    </RouterLink>
                </template>
            </div>
        </div>
    </section>
</template>

<style scoped>
.marquee {
    animation: slide linear 25s infinite;
    @apply w-fit flex gap-4;
}

@keyframes slide {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(calc(-50% - 8px));
    }
}
</style>
