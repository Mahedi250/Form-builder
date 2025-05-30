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
          :class="tab === 'visual' ? activeTabClass : inactiveTabClass"
          @click="tab = 'visual'"
        >
          Visual Mode
        </button>
      </div>
      <EditJsonBuilder
        v-if="tab === 'json'"
        :form="form"
        @update="updateForm"
      />
      <EditVisualBuilder
        v-else
        :form="form"
        @update="updateForm"
      />
      <div
        v-if="showSuccess"
        class="fixed top-6 right-6 bg-green-500 text-white px-4 py-2 rounded shadow z-50"
      >
        Form updated successfully!
      </div>
    </div>
  </Layout>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import Layout from '@/Layout/App.vue'
import EditJsonBuilder from '@/components/forms/EditJsonBuilder.vue'
import EditVisualBuilder from '@/components/forms/EditVisualBuilder.vue'

const props = defineProps<{ form: object }>()
const tab = ref('json')
const showSuccess = ref(false)
const activeTabClass = 'px-4 py-2 border-b-2 border-indigo-600 text-indigo-700 font-semibold focus:outline-none'
const inactiveTabClass = 'px-4 py-2 text-gray-500 hover:text-indigo-600 focus:outline-none'

function updateForm(updatedForm) {
  // Call your store or API to update the form here
  // On success:
  showSuccess.value = true
  setTimeout(() => (showSuccess.value = false), 2000)
}
</script>