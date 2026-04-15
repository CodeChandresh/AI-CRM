// resources/js/Components/Layout/Sidebar.vue

<template>
  <aside class="bg-gray-800 shadow-md w-64 p-4">
    <div class="flex flex-col">
      <div class="flex items-center justify-between mb-4">
        <img src="/logo.png" alt="Logo" class="w-8 h-8">
        <button @click="toggleSidebar">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <ul>
        <li v-for="(item, index) in menuItems" :key="index" class="mb-2">
          <a :href="item.href" :class="{ 'bg-gray-900': isActive(item.href) }" class="flex items-center py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
            <svg v-if="item.icon" :class="item.icon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"></svg>
            {{ item.label }}
          </a>
          <ul v-if="item.children">
            <li v-for="(child, index) in item.children" :key="index" class="mb-2">
              <a :href="child.href" :class="{ 'bg-gray-900': isActive(child.href) }" class="flex items-center py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg v-if="child.icon" :class="child.icon" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"></svg>
                {{ child.label }}
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
export default {
  data() {
    return {
      menuItems: [
        {
          label: 'Dashboard',
          href: '/dashboard',
          icon: 'heroicon-o-home'
        },
        {
          label: 'Leads',
          href: '/leads',
          icon: 'heroicon-o-chart-bar',
          children: [
            {
              label: 'New Leads',
              href: '/leads/new'
            },
            {
              label: 'Follow-up Leads',
              href: '/leads/follow-up'
            }
          ]
        },
        {
          label: 'Contacts',
          href: '/contacts',
          icon: 'heroicon-o-users'
        },
        {
          label: 'Accounts',
          href: '/accounts',
          icon: 'heroicon-o-building'
        },
        {
          label: 'Opportunities',
          href: '/opportunities',
          icon: 'heroicon-o-chart-bar'
        }
      ]
    }
  },
  methods: {
    toggleSidebar() {
      // Toggle sidebar visibility
    },
    isActive(href) {
      // Check if current route matches href
      return window.location.href === href;
    }
  }
}
</script>

<style scoped>
.sidebar {
  @apply fixed top-0 left-0 w-64 h-screen bg-gray-800;
}
</style>