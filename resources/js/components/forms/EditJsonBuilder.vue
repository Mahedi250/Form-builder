<template>
  <div class="space-y-4 w-full">
    <textarea
      v-model="jsonInput"
      class="w-full border rounded px-3 py-2 font-mono text-xs min-h-[300px]"
      placeholder="Paste your form JSON here"
    ></textarea>
    <div v-if="errorMsg" class="bg-red-500 text-white px-4 py-2 rounded shadow">{{ errorMsg }}</div>
    <div class="flex gap-2">
      <button
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
        @click="updateJsonForm"
        :disabled="isSaving"
      >{{ isSaving ? 'Updating...' : 'Update' }}</button>
      <button
        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
        type="button"
        @click="router.visit('/admin/forms-list')"
      >Cancel</button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { useFormStore } from '@/stores/formStore'

const props = defineProps<{ form: any }>()
const emit = defineEmits(['update'])

const formStore = useFormStore()
const jsonInput = ref('')
const errorMsg = ref('')
const isSaving = ref(false)

const allowedRootKeys = ['title', 'method', 'action', 'fields']
const allowedFieldKeys = [
  'type', 'name', 'label', 'placeholder', 'required', 'validation_rules', 'meta'
]

// Helper to filter the form object for display (no id fields)
function filterForm(obj: any) {
  if (!obj || typeof obj !== 'object') return {}
  const filtered: any = {}
  for (const key of allowedRootKeys) {
    if (key === 'fields' && Array.isArray(obj.fields)) {
      filtered.fields = obj.fields.map((f: any) => {
        const field: any = {}
        for (const k of allowedFieldKeys) {
          if (k === 'validation_rules') {
            field[k] = Array.isArray(f[k]) ? f[k] : []
          } else if (k === 'meta') {
            field[k] = f[k] !== undefined && f[k] !== null ? f[k] : {}
          } else if (f[k] !== undefined) {
            field[k] = f[k]
          } else if (k === 'required') {
            field[k] = false
          }
        }
        return field
      })
    } else if (obj[key] !== undefined) {
      filtered[key] = obj[key]
    }
  }
  return filtered
}

// Helper to add ids on submit
function addIds(obj: any, original: any) {
  const result: any = { ...obj }
  // Add form id
  result.id = original.id || `form_${Date.now()}`
  // Add field ids
  if (Array.isArray(result.fields)) {
    result.fields = result.fields.map((f: any, idx: number) => ({
      ...f,
      id: (original.fields && original.fields[idx] && original.fields[idx].id) || `field_${idx}_${Date.now()}`
    }))
  }
  return result
}

watch(
  () => props.form,
  (val) => {
    const filtered = filterForm(val)
    jsonInput.value = JSON.stringify(filtered, null, 2)
  },
  { immediate: true }
)

function validateFormJson(obj: any): string | null {
  // ...your validation logic...
  return null
}

async function updateJsonForm() {
  errorMsg.value = ''
  let parsed
  try {
    parsed = JSON.parse(jsonInput.value)
  } catch (e) {
    errorMsg.value = 'Invalid JSON.'
    return
  }
  const validationError = validateFormJson(parsed)
  if (validationError) {
    errorMsg.value = validationError
    return
  }
  isSaving.value = true
  // Add ids before submitting
  const withIds = addIds(parsed, props.form)
  const success = await formStore.updateForm(withIds)
  isSaving.value = false
  if (success) {
    emit('update', withIds)
    router.visit('/admin/forms-list')
  } else {
    errorMsg.value = 'Failed to update form.'
  }
}
</script>