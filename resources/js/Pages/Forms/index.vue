<template>
  <Layout>
    <div class="p-4 sm:p-8">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-indigo-700">Form List</h1>
        <button
          @click="router.visit('/admin/form/create')"
          class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-full shadow transition hover:curs hover:pointer cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Create Form
        </button>
      </div>
      <div v-if="showSuccess" class="fixed top-6 right-6 bg-green-500 text-white px-4 py-2 rounded shadow z-50">
        Form deleted successfully!
      </div>
      <div class="rounded-lg shadow">
        <div class="overflow-y-auto h-[400px]" >
          <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-indigo-100">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">Title</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">Method</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">Action</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">Fields</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider border-b">Options</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(form, i) in formStore.forms"
                :key="form.id"
                class="hover:bg-indigo-50 transition"
                style="overflow: visible;"
              >
                <td class="px-4 py-3 border-b">{{ i + 1 }}</td>
                <td class="px-4 py-3 border-b font-medium text-gray-800">{{ form.title }}</td>
                <td class="px-4 py-3 border-b text-gray-600">{{ form.method }}</td>
                <td class="px-4 py-3 border-b text-gray-600">{{ form.action }}</td>
                <td class="px-4 py-3 border-b">
                  <ul class="space-y-1">
                    <li
                      v-for="field in form.fields"
                      :key="field.id"
                      class="inline-block bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs mr-1 mb-1"
                    >
                      {{ field.label }} <span class="text-gray-400">({{ field.type }})</span>
                    </li>
                  </ul>
                </td>
                <td class="px-4 py-3 border-b !relative !overflow-visible">
                  <div class="relative inline-block text-left" @click.stop>
                    <button
                      @click="toggleDropdown(form.id)"
                      class="px-2 py-1 rounded hover:bg-indigo-100 focus:outline-none"
                    >
                      ⋮
                    </button>
                    <transition name="fade">
                      <div
                        v-if="dropdownOpen === form.id"
                        class="origin-top-right absolute right-0 mt-2 w-32 bg-white border rounded shadow-lg z-50"
                        style="min-width: 8rem;"
                      >
                        <button
                          @click="editForm(form.id)"
                          class="block w-full text-left px-4 py-2 text-sm hover:bg-indigo-50"
                        >
                          Edit
                        </button>
                        
                        <button
                          @click="deleteForm(form.id)"
                          class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                        >
                          Delete
                        </button>
                      </div>
                    </transition>
                  </div>
                </td>
              </tr>
              <tr v-if="!formStore.forms.length">
                <td colspan="6" class="text-center py-6 text-gray-400">No forms found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Layout from '@/Layout/App.vue'
import { useFormStore } from '@/stores/formStore'

const props = defineProps({ forms: Array })
const dropdownOpen = ref(null)
const showSuccess = ref(false)
const formStore = useFormStore()

// Sync props.forms to store state on mount and when props.forms changes
formStore.forms = props.forms || []

watch(
  () => props.forms,
  (newForms) => {
    formStore.forms = newForms || []
  }
)

function toggleDropdown(id) {
  dropdownOpen.value = dropdownOpen.value === id ? null : id
}

function editForm(id) {
  dropdownOpen.value = null
  router.visit(`/admin/form/edit/${id}/`)
}

async function deleteForm(id) {
  dropdownOpen.value = null
  if (confirm('Are you sure you want to delete this form?')) {
    const result = await formStore.removeForm(id)
    if (result === true) {
      showSuccess.value = true
      setTimeout(() => (showSuccess.value = false), 2000)
   
    }
  }
}

function handleClickOutside(event) {
  dropdownOpen.value = null
}
onMounted(() => {
  window.addEventListener('click', handleClickOutside)
})
onBeforeUnmount(() => {
  window.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
/* Optional: smooth fade for dropdown */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.15s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>