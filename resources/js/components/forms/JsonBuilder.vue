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
        @click="saveJsonForm"
        :disabled="isSaving"
      >{{ isSaving ? 'Saving...' : 'Save' }}</button>
      <button
        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
        type="button"
        @click="router.visit('/admin/forms-list')"
      >Cancel</button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useFormStore } from '@/stores/formStore'
import type { FormData, FormField } from '@/types/form'

const emit = defineEmits(['submitted'])

const jsonInput = ref('')
const errorMsg = ref('')
const isSaving = ref(false)
const formStore = useFormStore()

const allowedTypes = ['text', 'email', 'textarea', 'select']
const allowedValidationKeys = ['min', 'max', 'regex', 'email', 'number']

function validateFormJson(obj: any): string | null {
  if (typeof obj !== 'object' || obj === null) return 'JSON must be an object.'
  if (typeof obj.title !== 'string' || !obj.title.trim()) return 'Title is required.'
  if (typeof obj.method !== 'string' || !obj.method.trim()) return 'Method is required.'
  if (typeof obj.action !== 'string' || !obj.action.trim()) return 'Action is required.'
  if (!Array.isArray(obj.fields) || obj.fields.length === 0) return 'At least one field is required.'

  for (const [i, field] of obj.fields.entries()) {
    if (typeof field.type !== 'string' || !field.type) return `Field #${i + 1}: type is required.`
    if (!allowedTypes.includes(field.type)) return `Field #${i + 1}: type "${field.type}" is not allowed.`
    if (typeof field.name !== 'string' || !field.name) return `Field #${i + 1}: name is required.`
    if (typeof field.label !== 'string' || !field.label) return `Field #${i + 1}: label is required.`
    if (typeof field.required !== 'boolean') return `Field #${i + 1}: required must be boolean.`
    if (field.type === 'select') {
      if (!field.meta || !Array.isArray(field.meta.options) || field.meta.options.length < 1) {
        return `Field #${i + 1}: select fields require meta.options with at least one item.`
      }
      if (field.meta.options.some((opt: any) => typeof opt !== 'string' || !opt.trim())) {
        return `Field #${i + 1}: all options must be non-empty strings.`
      }
      // For select, validation_rules must not be present
      if (field.validation_rules && Object.keys(field.validation_rules).length > 0) {
        return `Field #${i + 1}: select fields must not have validation_rules.`
      }
    } else {
      // Validate validation_rules keys if present (for non-select fields)
      if (field.validation_rules && typeof field.validation_rules === 'object') {
        for (const key of Object.keys(field.validation_rules)) {
          if (!allowedValidationKeys.includes(key)) {
            return `Field #${i + 1}: validation_rules contains invalid key "${key}". Allowed: ${allowedValidationKeys.join(', ')}.`
          }
        }
      }
    }
  }
  return null
}

async function saveJsonForm() {
  errorMsg.value = ''
  let parsed: FormData
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
  try {
    await formStore.addForm(parsed)
    emit('submitted')
    jsonInput.value = ''
    router.visit('/admin/forms-list')
  } catch (e) {
    errorMsg.value = 'Failed to save form.'
  } finally {
    isSaving.value = false
  }
}
</script>