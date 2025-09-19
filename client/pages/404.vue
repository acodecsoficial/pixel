<script setup>
import notFound from '@/assets/images/404.png?url'
</script>

<template>
  <div class="not-found-page py-20 text-center">
    <img :src="notFound" class="not-found__img mx-auto mb-8 h-[100px]" />
    <h1 class="not-found__heading text-2xl text-center mb-4">{{ $t('front.not-found') }}</h1>
    <RouterLink to="/" class="text-primary">{{ $t('front.go-home') }}</RouterLink>
  </div>
</template>
