<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="h-16 flex items-center justify-center border-b">
        <span class="text-xl font-bold text-blue-600">Dashboard</span>
      </div>
      <nav class="flex-1 p-4">
        <ul class="space-y-2">
          <li>
            <Link href="/" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition">
              <span class="i-lucide-home w-5 h-5 mr-2"></span>
              Home
            </Link>
          </li>
          <li>
            <Link href="/dashboard" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition">
              <span class="i-lucide-user w-5 h-5 mr-2"></span>
              Profile
            </Link>
          </li>
          <li>
            <Link href="#" class="flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition">
              <span class="i-lucide-settings w-5 h-5 mr-2"></span>
              Settings
            </Link>
          </li>
        </ul>
      </nav>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 p-8">
      <div class="mt-6">
        <slot/>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<script>
// Named export for Layout
export default {
  name: 'Layout'
}
</script>