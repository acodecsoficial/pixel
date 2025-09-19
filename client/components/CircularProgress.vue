<script setup lang="ts">
const props = defineProps<{
  progress: number
}>()
</script>

<template>
  <svg
    viewBox="0 0 250 250"
    class="circular-progress"
    :style="{
      '--progress': props.progress,
    }"
  >
    <circle class="bg"></circle>
    <circle class="fg"></circle>
  </svg>
</template>

<style scoped>
.circular-progress {
  --size: 100%;
  --half-size: calc(var(--size) / 2);
  --stroke-width: 13px;
  --stroke-margin: 4px;
  --radius: calc((var(--size) - var(--stroke-width)) / 2);
  --circumference: calc(var(--radius) * pi * 2);
  --dash: calc((var(--progress) * var(--circumference)) / 100);

  position: absolute;
  width: calc(var(--size) + var(--stroke-width) + var(--stroke-margin));
  height: calc(var(--size) + var(--stroke-width) + var(--stroke-margin));
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1;
}

.circular-progress circle {
  cx: var(--half-size);
  cy: var(--half-size);
  r: var(--radius);
  stroke-width: var(--stroke-width);
  fill: none;
  stroke-linecap: round;
}

.circular-progress circle.bg {
  stroke: rgb(255 255 255 / 10%);
}

.circular-progress circle.fg {
  transform: rotate(-90deg);
  transform-origin: var(--half-size) var(--half-size);
  stroke-dasharray: var(--dash) calc(var(--circumference) - var(--dash));
  transition: stroke-dasharray 0.3s linear 0s;
  stroke: rgb(var(--primary-color) / 100%);
}
</style>
