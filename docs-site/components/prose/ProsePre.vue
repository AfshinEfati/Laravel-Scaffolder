<script setup lang="ts">
defineProps({
  code: {
    type: String,
    default: ''
  },
  language: {
    type: String,
    default: null
  },
  filename: {
    type: String,
    default: null
  },
  highlights: {
    type: Array as () => number[],
    default: () => []
  },
  meta: {
    type: String,
    default: null
  }
})
</script>

<template>
  <div class="relative group my-6 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-900 shadow-lg">
    <div v-if="filename" class="flex items-center px-4 py-2 border-b border-gray-800 bg-gray-900/50">
      <span class="text-xs font-mono text-gray-400">{{ filename }}</span>
    </div>
    <div class="absolute right-3 top-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
      <CopyButton :code="code" />
    </div>
    <pre :class="[\$attrs.class, 'p-4 overflow-x-auto text-sm leading-relaxed font-mono']"><slot /></pre>
  </div>
</template>

<style scoped>
pre {
  margin: 0 !important;
  background-color: transparent !important;
}
</style>
