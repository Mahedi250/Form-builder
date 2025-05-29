<template>
  <div class="space-y-6 w-full">
    <div class="p-4 border rounded mb-4 w-full">
      <h2 class="font-semibold mb-2">JSON Mode</h2>
      <textarea
        v-model="json"
        class="w-full h-48 border rounded p-2 font-mono"
        placeholder="Paste or edit JSON here"
      ></textarea>
    </div>
    <div class="flex gap-2">
      <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
      <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
const json = ref('')
</script>