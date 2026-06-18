<script setup>
const route = useRoute()
const { data: page } = await useAsyncData(`page-${route.path}`, () => queryContent(route.path).findOne())

if (!page.value && route.path === '/') {
  navigateTo('/en')
}

useContentHead(page)
</script>

<template>
  <div v-if="page">
    <article class="prose prose-slate dark:prose-invert max-w-none">
      <ContentRenderer :value="page" />
    </article>
  </div>
  <div v-else class="flex flex-col items-center justify-center h-64 gap-4">
    <p class="text-gray-500 text-xl font-medium">Page not found.</p>
    <NuxtLink to="/en" class="text-blue-600 hover:underline">Go to Home</NuxtLink>
  </div>
</template>
