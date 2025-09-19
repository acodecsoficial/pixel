<script setup lang="ts">
import { ref, onMounted } from "vue";
import useGlobalStore from "@/store/global";
import ChangeLangSelect from "@/components/ChangeLangSelect.vue";
import PixIcon from "@/assets/icons/pix.svg?component";
import InstagramIcon from "@/assets/icons/instagram.svg?component";
import YoutubeIcon from "@/assets/icons/youtube.svg?component";
import TelegramIcon from "@/assets/icons/telegram.svg?component";
import BeGambleAwareIcon from "@/assets/seals/BeGambleAware.svg?url";
import sslIcon from "@/assets/seals/ssl.svg?url";
import adultIcon from "@/assets/seals/adult.svg?url";
// @ts-ignore
import gamblingIcon from "@/assets/seals/gambling.png?url";

const global = useGlobalStore();

const siteName = location.host;
const address = "123 Barrack Road, Belize City, Belize";
const registrationNumber = "41556";

const showMMA = window.location.host.replace("www.", "") === "feier-ex.bet";
</script>

<template>
    <footer class="footer bg-surface px-7 pt-8 pb-20 w-full text-sm mt-12">
        <div class="flex flex-col md:flex-row items-center gap-5 pb-8">
            <RouterLink to="/">
                <img
                    :src="global.logoURL"
                    class="footer-logo w-[120px] min-w-[120px] block"
                    :alt="global.website_name"
                />
            </RouterLink>

            <div class="grow flex flex-col gap-y-0.5">
                <strong class="text-center md:text-left">{{
                    $t("front.footer.sac")
                }}</strong>
                <div
                    class="flex justify-center md:justify-start gap-3 flex-wrap [&>*]:text-sm [&>*]:opacity-70"
                >
                    <RouterLink class="footer_link" to="/terms">{{
                        $t("front.footer.terms")
                    }}</RouterLink>
                    <RouterLink class="footer_link" to="/betting-terms">{{
                        $t("front.footer.bet-terms")
                    }}</RouterLink>
                    <RouterLink class="footer_link" to="/aml-policy">{{
                        $t("front.footer.aml-policy")
                    }}</RouterLink>
                    <RouterLink class="footer_link" to="/privacy">{{
                        $t("front.footer.policy")
                    }}</RouterLink>
                    <RouterLink class="footer_link" to="/responsible-gaming">{{
                        $t("front.footer.responsibility")
                    }}</RouterLink>
                    <RouterLink class="footer_link" to="/user/refers">{{
                        $t("front.footer.affiliate")
                    }}</RouterLink>
                </div>
            </div>

            <div class="flex items-center">
                <div v-if="!showMMA" class="w-[150px]">
                    <strong class="text-base mb-2 block">{{
                        $t("front.footer.payment")
                    }}</strong>
                    <PixIcon class="h-[30px]" />
                </div>

                <div class="">
                    <strong class="text-base mb-2 block">{{
                        $t("front.footer.follow")
                    }}</strong>
                    <div class="flex gap-3 items-center">
                        <a
                            :href="global.social?.instagram"
                            v-if="global.social?.instagram"
                            class="footer__social-icon rounded-full p-2.5 bg-black/40"
                            target="_blank"
                        >
                            <InstagramIcon class="size-4" />
                        </a>
                        <a
                            :href="global.social?.youtube"
                            v-if="global.social?.youtube"
                            class="footer__social-icon rounded-full p-2.5 bg-black/40"
                            target="_blank"
                        >
                            <YoutubeIcon class="size-4" />
                        </a>
                        <a
                            :href="global.social?.telegram"
                            v-if="global.social?.telegram"
                            class="footer__social-icon rounded-full p-2.5 bg-black/40"
                            target="_blank"
                        >
                            <TelegramIcon class="size-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-t border-white/30" />

        <div class="py-8 text-center">
            <div class="flex flex-wrap gap-4 items-center justify-center">
                <a href="https://www.begambleaware.org/" target="_blank">
                    <img
                        :src="BeGambleAwareIcon"
                        title="BeGambleAware"
                        class="h-[28px]"
                    />
                </a>
                <a
                    href="https://www.gamblingtherapy.org/pt-br/"
                    target="_blank"
                >
                    <img
                        :src="gamblingIcon"
                        title="Terapia de jogo"
                        class="h-[28px]"
                    />
                </a>
                <img :src="sslIcon" title="Site seguro" class="h-[40px]" />
                <a href="/responsible-gaming" target="_blank">
                    <img
                        :src="adultIcon"
                        title="Para maiores de 18 anos"
                        class="size-[40px]"
                    />
                </a>
            </div>

            <div
                class="flex flex-col md:flex-row gap-x-4 gap-y-2 justify-center text-xs text-stone-400 text-center mt-5"
            >
                <div>
                    {{ $t("front.footer.sac") }}
                    <a
                        :href="'mailto:' + global.footer_emails?.SUPPORT_EMAIL"
                        class="text-stone-200"
                        >{{ global.footer_emails?.SUPPORT_EMAIL }}</a
                    >
                </div>
                <div>
                    {{ $t("front.footer.compliance") }}
                    <a
                        :href="'mailto:' + global.footer_emails?.LEGAL_EMAIL"
                        class="text-stone-200"
                        >{{ global.footer_emails?.LEGAL_EMAIL }}</a
                    >
                </div>
                <div>
                    {{ $t("front.footer.affiliates") }}
                    <a
                        :href="'mailto:' + global.footer_emails?.PARTNER_EMAIL"
                        class="text-stone-200"
                        >{{ global.footer_emails?.PARTNER_EMAIL }}</a
                    >
                </div>
            </div>
        </div>

        <!-- Texto legal adicional ao rodapé -->
        <!--
        <div class="mt-10 text-xs text-stone-400 w-full px-7 text-justify">
            <p class="mb-4">
                O santo.bet é operado pela Orakum N.V. (número de registro 141651, registrada em Abraham Mendez Chumaceiro Boulevard 03, Curaçao) como Titular da Licença (licença de Curaçao nº 8048/JAZ). A Zavbin LTD (número de registro HE 400081), com sede social localizada em Annis Marias 9, Mavrikios Court, Escritório/Apartamento 15, 3071 Limassol, Chipre, e a Maira LTD (número de registro ΗΕ 448572), com sede social localizada em Vizantiou 39, apartamento 31, 2064, Strovolos, Nicósia, Chipre, prestam serviços de processamento no site como Agentes de Cobrança. Todos os direitos reservados e protegidos por lei.
            </p>
            <p>
                A Orakum N.V. possui um pedido, OGL/2024/586/0786, para uma licença de jogo em andamento junto ao Conselho de Controle de Jogos de Curaçao. Até que esse processo seja concluído, com base em um acordo transitório, a empresa está autorizada a continuar suas operações sob este Certificado de Operação temporário emitido pelo GCB para continuar suas operações. Este Certificado de Operação está sujeito à Portaria Nacional sobre Jogos de Risco Offshore (Landsverordening buitengaatse hazardspelen, P.B. 1993, n.º 63) (NOOGH).
            </p>
        </div>-->

        <ChangeLangSelect />
    </footer>
</template>
