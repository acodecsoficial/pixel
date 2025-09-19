<script setup lang="ts">
import useModalStore from '@/store/modals';

const modals = useModalStore()

function close() {
  modals.showBonusRulesModal = false;
}
</script>

<template>
  <Modal
    :on-close="close"
    :modal-width="420"
  >
    <h2 class="text-2xl font-semibold flex items-center gap-3">
      <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M6 3H18C20.2 3 22 4.8 22 7C22 8.7 21 10.1 19.5 10.7V7C19.5 6.6 19.3 6.2 19.1 5.9C18.9 5.6 18.4 5.5 18 5.5H6C5.6 5.5 5.2 5.7 4.9 5.9C4.6 6.1 4.5 6.6 4.5 7V10.7C3 10.1 2 8.7 2 7C2 4.8 3.8 3 6 3Z" fill="#9CCE13" fill-opacity="0.3"></path><path clip-rule="evenodd" d="M18 7V19C18 20.1 17.1 21 16 21H8C6.9 21 6 20.1 6 19V7H18ZM10.5 14.5L11.2 15.2V11C11.2 10.6 11.5 10.2 12 10.2C12.5 10.2 12.8 10.5 12.8 11V15.2L13.5 14.5C13.8 14.2 14.3 14.2 14.6 14.5C14.9 14.8 14.9 15.3 14.6 15.6L13.3 16.9C12.6 17.6 11.5 17.6 10.8 16.9L9.5 15.6C9.2 15.3 9.2 14.8 9.5 14.5C9.8 14.2 10.2 14.2 10.5 14.5Z" fill="#9CCE13" fill-rule="evenodd"></path></svg>
      Regras do Bônus
    </h2>

    <div class="card bg-white/5 rounded-lg py-5 px-6 mt-6">
      <article class="rule-rows">
        <p>Bônus de 1° Depósito: <span>100% até $&nbsp;7.000,00</span></p>
        <p>Apostas Cassino: <span>500x</span></p>
        <p>Apostas Esportes: <span>100x</span></p>
        <p>Tempo de Expiração Cassino: <span>24 horas</span></p>
        <p>Tempo de Expiração Esportes: <span>48 horas</span></p>
        <p>Valor Máximo de Saque de Bônus: <span>3x</span></p>
      </article>

      <article class="rules-warning">
        <div class="flex items-center gap-4 -mb-1">
          <svg viewBox="0 0 512 512" class="size-16" xmlns="http://www.w3.org/2000/svg"><path d="M256 320C269.3 320 280 309.3 280 296v-128c0-13.25-10.75-24-23.1-24S232 154.8 232 168v128C232 309.3 242.8 320 256 320zM256 353.1c-17.36 0-31.44 14.08-31.44 31.44C224.6 401.9 238.6 416 256 416s31.44-14.08 31.44-31.44C287.4 367.2 273.4 353.1 256 353.1z" fill="currentColor"></path><path d="M506.3 417l-213.3-364c-16.33-28-57.54-28-73.98 0l-213.2 364C-10.59 444.9 9.849 480 42.74 480h426.6C502.1 480 522.6 445 506.3 417zM232 168c0-13.25 10.75-24 24-24S280 154.8 280 168v128c0 13.25-10.75 24-23.1 24S232 309.3 232 296V168zM256 416c-17.36 0-31.44-14.08-31.44-31.44c0-17.36 14.07-31.44 31.44-31.44s31.44 14.08 31.44 31.44C287.4 401.9 273.4 416 256 416z" fill="currentColor" opacity="0.4"></path></svg>
          <div class="m-0 p-0 leading-4">Para realizar o saque do bônus para carteira real, você precisa cumprir as regras:</div>
        </div>
        <p>
          <strong>Cassino: </strong><br>
          Voce deve acessar algum jogo disponível na categoria Jogar com Bônus
        </p>
        <p>
          <strong>Apostas esportivas: </strong><br>
          Bilhetes de apostas Multipla ODD acima de 3.00
        </p>
        <p>Para usar o saldo bônus você não pode possuir o valor da aposta na sua carteira de saldo real.</p>
      </article>
    </div>
  </Modal>
</template>

<style scoped>
.rule-rows {
  @apply flex flex-col gap-3;
}

.rule-rows p {
  @apply font-medium;
}

.rule-rows span {
  @apply bg-primary/10 text-primary text-sm font-medium rounded-md p-1.5 py-0.5;
}

.rules-warning {
  @apply text-amber-500 border-t border-t-amber-500 pt-2.5 mt-6 text-sm flex flex-col gap-4;
}

.rules-warning p {
  @apply border-l border-l-amber-500 pl-3;
}
</style>
