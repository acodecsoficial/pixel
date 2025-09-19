<script setup lang="ts">
import { ref, type InputHTMLAttributes } from 'vue'
import cn from '@/helpers/classnames'

interface Props extends /* @vue-ignore */ InputHTMLAttributes {
  label?: string,
  modelValue: InputHTMLAttributes['value'],
  wrapperClass?: string,
}

const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'update:modelValue', payload: string): void
}>();

const inputRef = ref<HTMLInputElement | null>(null);
</script>

<template>
  <slot name="before" />

  <label
    :class="cn('text-input block bg-white/10 rounded-custom-max px-4 pt-3 pb-3 mb-3 group focus-within:bg-white/15 transition-colors', {
      'cursor-not-allowed': $attrs.disabled,
    }, props.wrapperClass)">
    <div class="text-input__label opacity-80 relative text-xs top-0 -mt-0.5 mb-0.5" v-if="props.label">
      {{ label }}
      <span v-if="'required' in $attrs" class="text-[#ff6969] text-sm">*</span>
    </div>

    <div class="flex gap-2 items-center">
      <slot name="before_input" />

      <input
        ref="inputRef"
        :type="props.type ?? 'text'"
        class="text-input__input bg-transparent outline-none flex-grow w-full"
        :class="{ 'opacity-50 cursor-not-allowed': $attrs.disabled }"
        v-bind="$attrs"
        :value="modelValue"
        @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
      >

      <slot name="after_input" />
    </div>
  </label>

  <slot name="after" />
</template>

<script lang="ts">
export default {
  inheritAttrs: false
}
</script>
