<script setup>
const route = useRoute()
const isFa = computed(() => route.path.startsWith('/fa'))
const dir = computed(() => isFa.value ? 'rtl' : 'ltr')

useHead({
  htmlAttrs: {
    lang: computed(() => isFa.value ? 'fa' : 'en'),
    dir: dir
  }
})

const { data: navigation } = await useAsyncData('navigation', () => {
  const locale = isFa.value ? 'fa' : 'en'
  return fetchContentNavigation(queryContent(locale))
})

const colorMode = useColorMode()
const toggleColorMode = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}

const isSidebarOpen = ref(false)
</script>

<template>
  <div :dir="dir" class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans">
    <!-- Header -->
    <header class="sticky top-0 z-40 w-full backdrop-blur border-b border-gray-200 dark:border-gray-800 bg-white/75 dark:bg-gray-900/75">
      <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-2">
            <span class="sr-only">Toggle Sidebar</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
          </button>
          <NuxtLink :to="isFa ? '/fa' : '/en'" class="text-xl font-bold tracking-tight">
            Laravel Scaffolder
          </NuxtLink>
        </div>

        <div class="flex items-center gap-2 lg:gap-4">
          <Search />
          <NuxtLink :to="isFa ? '/en' : '/fa'" class="text-sm font-medium hover:text-blue-600 transition-colors">
            {{ isFa ? 'English' : 'فارسی' }}
          </NuxtLink>
          <button @click="toggleColorMode" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <ClientOnly>
              <span v-if="colorMode.value === 'dark'">☀️</span>
              <span v-else>🌙</span>
            </ClientOnly>
          </button>
          <a href="https://github.com/AfshinEfati/Laravel-Scaffolder" target="_blank" class="hidden sm:block p-2 hover:text-blue-600 transition-colors">
             GitHub
          </a>
        </div>
      </div>
    </header>

    <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-8 py-8">
      <!-- Sidebar -->
      <aside :class="[
        'fixed lg:static inset-y-0 z-30 w-64 transform transition-transform duration-300 lg:translate-x-0 bg-white dark:bg-gray-900 lg:bg-transparent overflow-y-auto pt-20 lg:pt-0',
        isFa ? (isSidebarOpen ? 'right-0 shadow-xl' : 'translate-x-full lg:translate-x-0') : (isSidebarOpen ? 'left-0 shadow-xl' : '-translate-x-full lg:translate-x-0')
      ]">
        <nav class="space-y-6">
          <div v-for="group in navigation" :key="group._path">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 px-3">
              {{ group.title }}
            </h3>
            <ul class="space-y-1">
              <li v-for="item in group.children" :key="item._path">
                <NuxtLink :to="item._path" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200" active-class="!text-blue-600 !bg-blue-50 dark:!bg-blue-900/20 font-bold">
                  {{ item.title }}
                </NuxtLink>
              </li>
            </ul>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 min-w-0">
        <slot />
      </main>
    </div>

    <!-- Mobile Overlay -->
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden backdrop-blur-sm"></div>
  </div>
</template>

<style>
.prose {
  max-width: none;
}
.prose h1 {
  @apply text-4xl font-extrabold mb-8 tracking-tight;
}
.prose h2 {
  @apply text-2xl font-bold mt-12 mb-4 pb-2 border-b border-gray-100 dark:border-gray-800;
}
.prose p {
  @apply leading-relaxed mb-4 text-gray-700 dark:text-gray-300;
}
.prose code {
  @apply bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-pink-600 dark:text-pink-400 font-mono text-[0.9em] font-medium;
}
.prose pre code {
  @apply bg-transparent p-0 text-inherit font-normal;
}
.prose a {
  @apply text-blue-600 dark:text-blue-400 no-underline hover:underline font-medium;
}
.prose ul {
  @apply list-disc list-inside mb-4 space-y-2;
}
.prose table {
  @apply w-full border-collapse mb-8 text-sm;
}
.prose th {
  @apply bg-gray-50 dark:bg-gray-800/50 text-left p-3 font-bold border border-gray-200 dark:border-gray-700;
}
[dir="rtl"] .prose th {
  @apply text-right;
}
.prose td {
  @apply p-3 border border-gray-200 dark:border-gray-700;
}
</style>
