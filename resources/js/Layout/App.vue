<template>
  <div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col rounded-tr-3xl rounded-br-3xl m-2">
      <div class="h-20 flex items-center justify-center border-b">
        <span class="text-2xl font-extrabold text-indigo-600 tracking-wide">Admin Panel</span>
      </div>
      <nav class="flex-1 p-4">
        <ul class="space-y-1">
          <!-- Home menu -->
          <li>
            <Link
              href="/admin/home"
              :class="menuClass('home')"
              class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 gap-2"
            >
              <!-- Home icon -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h3m10-11v11a1 1 0 01-1 1h-3m-6 0h6"></path>
              </svg>
              Home
            </Link>
          </li>
          <!-- Forms menu -->
          <li>
            <Link
              href="/admin/forms-list"
              :class="menuClass('forms-list')"
              class="w-full flex items-center px-4 py-3 rounded-lg transition-all duration-200 gap-2"
            >
              <!-- Forms icon (clipboard) -->
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="9" y="2" width="6" height="4" rx="1" stroke="currentColor" stroke-width="2" fill="none"/>
                <rect x="4" y="6" width="16" height="16" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
              </svg>
              Forms
            </Link>
          </li>
        </ul>
      </nav>
      <div class="p-4 border-t">
        <div class="flex items-center space-x-3 mb-3">
          
          <div>
            <!-- User info can go here -->
          </div>
        </div>
        <form @submit.prevent="logout">
          <button
            type="submit"
            class="w-full bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200 transition text-sm font-semibold"
          >
            Logout
          </button>
        </form>
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
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()

function menuClass(menu) {
  const current =
    (page.url === '/' && menu === 'home') ||
    (page.url.startsWith('/forms-list') && menu === 'forms-list') ||
    (page.url.startsWith('/form/create') && menu === '/form/create');

  return [
    current
      ? 'bg-indigo-100 text-indigo-700 font-semibold shadow'
      : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700'
  ]
}

function logout() {
  router.post('/logout')
}
</script>

<script>
export default {
  name: 'Layout'
}
</script>