<template>
  <div class="space-y-6 w-full">
    <div class="flex gap-6">
      <!-- Side Menu: Field Types (Draggable Items, only actual field UI) -->
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

      <!-- Form Builder Area (Drop Zone + Draggable Reorder, only field UI with label and remove) -->
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
    <!-- Actions -->
    <div class="flex gap-2">
      <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
      <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'

const availableFields = [
  { type: 'text', label: 'Text Field', name: 'name', placeholder: 'Enter your name', required: false },
  { type: 'email', label: 'Email Field', name: 'email', placeholder: 'Enter your email', required: false },
  { type: 'textarea', label: 'Textarea', name: 'message', placeholder: 'Enter your message', required: false },
  { type: 'select', label: 'Select', name: 'select', placeholder: '', required: false, options: ['Option 1', 'Option 2'], optionsString: 'Option 1, Option 2' }
]

const builderFields = ref([])
const selectedField = ref(null)
let dragField = null

function onDragStart(field) {
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

function removeField(idx) {
  if (selectedField.value && builderFields.value[idx].id === selectedField.value.id) {
    selectedField.value = null
  }
  builderFields.value.splice(idx, 1)
}

function selectField(field) {
  selectedField.value = field
}

function updateOptions(field) {
  field.options = field.optionsString.split(',').map(opt => opt.trim()).filter(Boolean)
}
</script>

<style scoped>
button[title="Remove"] {
  cursor: pointer;
  transition: background 0.15s;
}
button[title="Remove"]:hover {
  background: #fee2e2;
}
</style>