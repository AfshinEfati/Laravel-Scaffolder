<script setup>
const route = useRoute()
const isFa = computed(() => route.path.startsWith('/fa'))

if (route.path === '/') {
  navigateTo('/en')
}
</script>

<template>
  <div>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>

<style>
@import "~/assets/css/main.css";
</style>
