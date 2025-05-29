<template>
  <Layout>
    <div class="w-full max-w-6xl mx-auto p-0 sm:p-6 bg-white rounded-xl shadow">
      <div class="flex border-b mb-6 w-full">
        <button
          :class="tab === 'json' ? activeTabClass : inactiveTabClass"
          @click="tab = 'json'"
        >
          JSON Mode
        </button>
        <button
          :class="tab === 'builder' ? activeTabClass : inactiveTabClass"
          @click="tab = 'builder'"
        >
          Builder Mode
        </button>
      </div>
      <JsonBuilder v-if="tab === 'json'" />
      <VisualBuilder v-else />
    </div>
  </Layout>
</template>

<script setup>
import { ref } from 'vue'
import Layout from '@/Layout/App.vue'
import JsonBuilder from '@/components/forms/JsonBuilder.vue'
import VisualBuilder from '@/components/forms/VisualBuilder.vue'

const tab = ref('json')
const activeTabClass = 'px-4 py-2 border-b-2 border-indigo-600 text-indigo-700 font-semibold focus:outline-none'
const inactiveTabClass = 'px-4 py-2 text-gray-500 hover:text-indigo-600 focus:outline-none'
</script>