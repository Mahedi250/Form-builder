<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form @submit.prevent="submit" class="bg-white p-8 rounded shadow w-full max-w-sm">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <div class="mb-4">
        <label class="block mb-1 text-sm">Email</label>
        <input v-model="form.email" type="email" class="w-full border rounded px-3 py-2" required autofocus />
        <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
      </div>
      <div class="mb-4">
        <label class="block mb-1 text-sm">Password</label>
        <input v-model="form.password" type="password" class="w-full border rounded px-3 py-2" required />
        <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
      </div>
      <div class="mb-4 flex items-center">
        <input v-model="form.remember" type="checkbox" id="remember" class="mr-2" />
        <label for="remember" class="text-sm">Remember me</label>
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Login</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.post('/login')
}
</script>