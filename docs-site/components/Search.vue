<script setup>
const { data: searchResults } = await useAsyncData('search', () => searchContent(''))
const q = ref('')
const results = computed(() => {
  if (!q.value) return []
  return searchResults.value.filter(item => 
    item.title.toLowerCase().includes(q.value.toLowerCase()) || 
    item.description?.toLowerCase().includes(q.value.toLowerCase())
  ).slice(0, 10)
})

const isFa = computed(() => useRoute().path.startsWith('/fa'))
</script>

<template>
  <div class="relative group">
    <div class="relative">
      <input 
        v-model="q"
        type="text" 
        :placeholder="isFa ? 'جستجو...' : 'Search...'"
        class="w-40 lg:w-64 bg-gray-100 dark:bg-gray-800 border-none rounded-lg py-2 px-4 text-sm focus:ring-2 focus:ring-blue-500 transition-all outline-none"
      >
      <div v-if="results.length > 0" class="absolute top-full mt-2 w-full bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
        <NuxtLink 
          v-for="item in results" 
          :key="item._path"
          :to="item._path"
          @click="q = ''"
          class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b last:border-none border-gray-100 dark:border-gray-700"
        >
          <div class="text-sm font-bold">{{ item.title }}</div>
          <div class="text-xs text-gray-500 truncate">{{ item.description }}</div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
