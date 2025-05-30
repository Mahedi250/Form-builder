<template>
  <div class="space-y-6 w-full">
    <!-- Top Section: Title, Method, Action -->
    <div class="flex flex-wrap gap-4 items-end mb-4">
      <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-medium mb-1">Form Title</label>
        <input v-model="formTitle" class="w-full border rounded px-2 py-1 text-xs" placeholder="Form Title" />
      </div>
      <div class="min-w-[120px]">
        <label class="block text-xs font-medium mb-1">Method</label>
        <select v-model="formMethod" class="w-full border rounded px-2 py-1 text-xs">
          <option value="POST">POST</option>
          <option value="GET">GET</option>
          <option value="PUT">PUT</option>
          <option value="DELETE">DELETE</option>
        </select>
      </div>
      <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-medium mb-1">Action</label>
        <input v-model="formAction" class="w-full border rounded px-2 py-1 text-xs" placeholder="/api/form" />
      </div>
    </div>

    <div class="flex gap-6">
      <!-- Side Menu: Field Types (Draggable Items) -->
      <div class="w-1/4 bg-indigo-50 p-4 rounded border h-[400px] overflow-y-auto">
        <h3 class="font-semibold mb-4 text-indigo-700">Drag Field</h3>
        <div
          v-for="field in availableFields"
          :key="field.type"
          class="mb-4"
          draggable="true"
          @dragstart="onDragStart(field)"
        >
          <div class="bg-white border rounded px-3 py-2 shadow flex flex-col gap-2 cursor-move hover:bg-indigo-100 transition">
            <input
              v-if="field.type === 'text' || field.type === 'email'"
              :type="field.type"
              class="w-full border rounded px-2 py-1 text-xs"
              :placeholder="field.placeholder"
              disabled
            />
            <textarea
              v-else-if="field.type === 'textarea'"
              class="w-full border rounded px-2 py-1 text-xs"
              :placeholder="field.placeholder"
              disabled
            ></textarea>
            <select
              v-else-if="field.type === 'select'"
              class="w-full border rounded px-2 py-1 text-xs"
              disabled
            >
              <option v-for="opt in field.options" :key="opt">{{ opt }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Form Builder Area (Drop Zone + Draggable Reorder) -->
      <div
        class="flex-1 bg-white border rounded p-4 min-h-[400px] max-h-[400px] overflow-y-auto"
        @dragover.prevent
        @drop="onDrop"
      >
        <draggable
          v-model="builderFields"
          item-key="id"
          class="space-y-3"
          handle=".drag-handle"
        >
          <template #item="{ element: field, index }">
            <div
              class="bg-indigo-50 border rounded p-2 flex items-center gap-2 relative group cursor-pointer"
              :class="selectedField && selectedField.id === field.id ? 'ring-2 ring-indigo-400' : ''"
              @click="selectField(field)"
              style="min-height: 48px;"
            >
              <span class="drag-handle cursor-move text-lg text-indigo-400 mr-2" title="Drag">&#9776;</span>
              <div class="flex-1 flex items-center gap-2">
                <label class="block text-xs font-medium text-indigo-700 mb-0 min-w-[60px]">{{ field.label }}</label>
                <input
                  v-if="field.type === 'text' || field.type === 'email'"
                  :type="field.type"
                  class="border rounded px-2 py-1 text-xs flex-1"
                  :placeholder="field.placeholder"
                  :required="field.required"
                  disabled
                />
                <textarea
                  v-else-if="field.type === 'textarea'"
                  class="border rounded px-2 py-1 text-xs flex-1"
                  :placeholder="field.placeholder"
                  :required="field.required"
                  disabled
                ></textarea>
                <select
                  v-else-if="field.type === 'select'"
                  class="border rounded px-2 py-1 text-xs flex-1"
                  :required="field.required"
                  disabled
                >
                  <option v-for="opt in field.options" :key="opt">{{ opt }}</option>
                </select>
              </div>
              <button
                @click.stop="removeField(index)"
                class="text-red-500 hover:bg-red-100 rounded-full text-xs ml-2 transition"
                style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                title="Remove"
              >✕</button>
            </div>
          </template>
        </draggable>
        <div v-if="builderFields.length === 0" class="text-gray-400 text-center py-10">
          Drag fields from left to build your form
        </div>
      </div>

      <!-- Field Options Sidebar -->
      <div class="w-1/4 bg-gray-50 p-4 rounded border h-[400px] overflow-y-auto" v-if="selectedField">
        <h3 class="font-semibold mb-2">Edit Field</h3>
        <div class="mb-2">
          <label class="block text-xs font-medium mb-1">Label</label>
          <input v-model="selectedField.label" class="w-full border rounded px-2 py-1 text-xs" />
        </div>
        <div class="mb-2">
          <label class="block text-xs font-medium mb-1">Name</label>
          <input v-model="selectedField.name" class="w-full border rounded px-2 py-1 text-xs" />
        </div>
        <div class="mb-2">
          <label class="block text-xs font-medium mb-1">Placeholder</label>
          <input v-model="selectedField.placeholder" class="w-full border rounded px-2 py-1 text-xs" />
        </div>
        <div class="mb-2">
          <label class="block text-xs font-medium mb-1">Required</label>
          <input type="checkbox" v-model="selectedField.required" class="mr-2" />
          <span class="text-xs">Yes</span>
        </div>
        <!-- User-friendly validation rules: checkboxes -->
        <div class="mb-2">
          <label class="block text-xs font-medium mb-1">Validation Rules</label>
          <div class="flex flex-col gap-1">
            <label v-if="selectedField.type === 'email'" class="flex items-center gap-2 text-xs">
              <input type="checkbox" value="email" v-model="selectedField.validations" />
              Email
            </label>
            <label v-if="selectedField.type === 'text'" class="flex items-center gap-2 text-xs">
              <input type="checkbox" value="number" v-model="selectedField.validations" />
              Number
            </label>
            <label v-if="selectedField.type === 'text' || selectedField.type === 'textarea'" class="flex items-center gap-2 text-xs">
              <input type="checkbox" value="min" v-model="selectedField.validations" />
              Min Length
            </label>
            <label v-if="selectedField.type === 'text' || selectedField.type === 'textarea'" class="flex items-center gap-2 text-xs">
              <input type="checkbox" value="max" v-model="selectedField.validations" />
              Max Length
            </label>
            <label v-if="selectedField.type === 'text' || selectedField.type === 'textarea'" class="flex items-center gap-2 text-xs">
              <input type="checkbox" value="regex" v-model="selectedField.validations" />
              Regex
            </label>
          </div>
        </div>
        <div v-if="selectedField.validations && selectedField.validations.includes('min')" class="mb-2">
          <label class="block text-xs font-medium mb-1">Min Length</label>
          <input v-model.number="selectedField.min" type="number" min="0" class="w-full border rounded px-2 py-1 text-xs" />
        </div>
        <div v-if="selectedField.validations && selectedField.validations.includes('max')" class="mb-2">
          <label class="block text-xs font-medium mb-1">Max Length</label>
          <input v-model.number="selectedField.max" type="number" min="0" class="w-full border rounded px-2 py-1 text-xs" />
        </div>
        <div v-if="selectedField.validations && selectedField.validations.includes('regex')" class="mb-2">
          <label class="block text-xs font-medium mb-1">Regex Pattern</label>
          <input v-model="selectedField.pattern" class="w-full border rounded px-2 py-1 text-xs" placeholder="e.g. ^[A-Za-z]+$" />
        </div>
        <div v-if="selectedField.type === 'select'" class="mb-2">
          <label class="block text-xs font-medium mb-1">Options (comma separated)</label>
          <input
            v-model="selectedField.optionsString"
            @input="updateOptions(selectedField)"
            class="w-full border rounded px-2 py-1 text-xs"
            placeholder="Option 1, Option 2"
          />
        </div>
      </div>
    </div>
    <!-- Live Preview -->
    <div class="p-4 border rounded mb-4 bg-indigo-50 w-full min-h-[200px] max-h-[200px] overflow-y-auto">
      <h2 class="font-semibold mb-2">Live Preview</h2>
      <form class="space-y-3">
        <div v-for="field in builderFields" :key="field.id">
          <label class="block font-medium mb-1 text-xs">{{ field.label }}</label>
          <input
            v-if="field.type === 'text' || field.type === 'email'"
            :type="field.type"
            class="w-full border rounded px-2 py-1 text-xs"
            :placeholder="field.placeholder"
            :required="field.required"
            disabled
          />
          <textarea
            v-else-if="field.type === 'textarea'"
            class="w-full border rounded px-2 py-1 text-xs"
            :placeholder="field.placeholder"
            :required="field.required"
            disabled
          ></textarea>
          <select
            v-else-if="field.type === 'select'"
            class="w-full border rounded px-2 py-1 text-xs"
            :required="field.required"
            disabled
          >
            <option v-for="opt in field.options" :key="opt">{{ opt }}</option>
          </select>
        </div>
      </form>
    </div>
    <!-- Error Message -->
    <div v-if="errorMsg" class="fixed top-6 right-6 bg-red-500 text-white px-4 py-2 rounded shadow z-50">
      {{ errorMsg }}
    </div>
    <!-- Actions -->
    <div class="flex gap-2">
      <button
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 hover:cursor-pointer transition"
        @click="updateForm"
        type="button"
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
import draggable from 'vuedraggable'
import { useFormStore } from '@/stores/formStore'
import type { FormData, FormField, ValidationRules } from '@/types/form'

const props = defineProps<{ form: FormData }>()
const emit = defineEmits(['update'])

const formStore = useFormStore()
const formTitle = ref('')
const formMethod = ref('POST')
const formAction = ref('')
const builderFields = ref<any[]>([])
const selectedField = ref<any | null>(null)
let dragField: any | null = null
const isSaving = ref(false)
const errorMsg = ref('')

const availableFields: any[] = [
  { type: 'text', label: 'Text Field', name: 'name', placeholder: 'Enter your name', required: false, id: 1 },
  { type: 'email', label: 'Email Field', name: 'email', placeholder: 'Enter your email', required: false, id: 2 },
  { type: 'textarea', label: 'Textarea', name: 'message', placeholder: 'Enter your message', required: false, id: 3 },
  { type: 'select', label: 'Select', name: 'select', placeholder: '', required: false, options: ['Option 1', 'Option 2'], optionsString: 'Option 1, Option 2', id: 4 }
]

// Prefill on mount or when prop changes
watch(
  () => props.form,
  (val) => {
    if (val) {
      formTitle.value = val.title
      formMethod.value = val.method
      formAction.value = val.action
      builderFields.value = val.fields.map((field, idx) => {
        const f = JSON.parse(JSON.stringify(field))
        f.id = f.id || Date.now() + idx
        if (f.type === 'select') {
          f.optionsString = (f.meta?.options || []).join(', ')
          f.options = f.meta?.options || []
        }
        return f
      })
    }
  },
  { immediate: true }
)

function onDragStart(field: any) {
  dragField = {
    ...JSON.parse(JSON.stringify(field)),
    id: Date.now() + Math.random(),
    options: field.options ? [...field.options] : [],
    optionsString: field.options ? field.options.join(', ') : '',
    validations: [],
    min: undefined,
    max: undefined,
    pattern: ''
  }
}

function onDrop() {
  if (dragField) {
    builderFields.value.push(dragField)
    selectedField.value = dragField
    dragField = null
  }
}

function removeField(idx: number) {
  if (selectedField.value && builderFields.value[idx].id === selectedField.value.id) {
    selectedField.value = null
  }
  builderFields.value.splice(idx, 1)
}

function selectField(field: any) {
  selectedField.value = field
}

function updateOptions(field: any) {
  field.options = field.optionsString?.split(',').map((opt: string) => opt.trim()).filter(Boolean)
}

async function updateForm() {
  errorMsg.value = ''
  // Validation
  if (!formTitle.value.trim()) {
    errorMsg.value = 'Form title is required.'
    return
  }
  if (!formMethod.value.trim()) {
    errorMsg.value = 'Form method is required.'
    return
  }
  if (!formAction.value.trim()) {
    errorMsg.value = 'Form action is required.'
    return
  }
  if (builderFields.value.length === 0) {
    errorMsg.value = 'At least one field is required.'
    return
  }
  for (const [i, field] of builderFields.value.entries()) {
    if (!field.type || !field.name || !field.label) {
      errorMsg.value = `Field #${i + 1}: type, name, and label are required.`
      return
    }
    if (field.type === 'select') {
      if (!Array.isArray(field.options) || field.options.length < 1) {
        errorMsg.value = `Field #${i + 1}: select fields require options with at least one item.`
        return
      }
      if (field.options.some((opt: any) => typeof opt !== 'string' || !opt.trim())) {
        errorMsg.value = `Field #${i + 1}: all options must be non-empty strings.`
        return
      }
    }
  }
  isSaving.value = true
  // Prepare the updated form object
  const updatedForm = {
    ...props.form,
    title: formTitle.value,
    method: formMethod.value,
    action: formAction.value,
    fields: builderFields.value.map(f => {
      if (f.type === 'select') {
        const { optionsString, ...rest } = f
        return { ...rest, meta: { options: f.options } }
      }
      return f
    })
  }
  const success = await formStore.updateForm(updatedForm)
  isSaving.value = false
  if (success) {
    emit('update', updatedForm)
    router.visit('/admin/forms-list')
  } else {
    errorMsg.value = 'Failed to update form.'
  }
}
</script>