<template>
  <div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col rounded-tr-3xl rounded-br-3xl m-2">
      <div class="h-20 flex items-center justify-center border-b">
        <span class="text-2xl font-extrabold text-indigo-600 tracking-wide">Dashboard</span>
      </div>
      <nav class="flex-1 p-4">
        <ul class="space-y-1">

           <li>
            <Link
              href="/form/create"
              :class="menuClass('/form/create')"
              class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z"></path>
              </svg>
              Create Form
            </Link>
          </li>
          <li>
            <Link
              href="/forms-list"
              :class="menuClass('forms-list')"
              class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h3m10-11v11a1 1 0 01-1 1h-3m-6 0h6"></path>
              </svg>
              Forms List
            </Link>
          </li>
         
          <li>
            <Link
              href="/settings"
              :class="menuClass('settings')"
              class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Settings
            </Link>
          </li>
        </ul>
      </nav>
      <div class="p-4 border-t">
        <div class="flex items-center space-x-3">
          <span class="inline-block w-10 h-10 bg-indigo-200 rounded-full"></span>
          <div>
            <div class="font-semibold text-gray-800">Maruf Hasan</div>
            <div class="text-xs text-gray-400">Admin</div>
          </div>
        </div>
      </div>
    </aside>
    <!-- Main Content -->
    <main class="flex-1 p-4">
      <div class="bg-white rounded-2xl shadow-lg p-6 min-h-[90vh]">
        <slot/>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

function menuClass(menu) {
  const current =
    (page.url === '/' && menu === 'home') ||
    (page.url.startsWith('/forms-list') && menu === 'forms-list') ||
    (page.url.startsWith('/dashboard') && menu === 'dashboard') ||
    (page.url.startsWith('/form/create') && menu === '/form/create');

  return [
    current
      ? 'bg-indigo-100 text-indigo-700 font-semibold shadow'
      : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700'
  ]
}
</script>

<script>
export default {
  name: 'Layout'
}
</script>