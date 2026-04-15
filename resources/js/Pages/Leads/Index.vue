// resources/js/Pages/Leads/Index.vue

<template>
  <div>
    <InertiaHead title="Leads" />
    <h1 class="text-3xl font-bold mb-4">Leads</h1>
    <div class="flex justify-between mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="openCreateModal"
      >
        Create Lead
      </button>
      <form @submit.prevent="searchLeads">
        <input
          v-model="searchQuery"
          type="search"
          placeholder="Search leads"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        />
      </form>
    </div>
    <div class="flex justify-between mb-4">
      <select
        v-model="sortField"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >
        <option value="name">Name</option>
        <option value="email">Email</option>
        <option value="phone">Phone</option>
        <option value="created_at">Created At</option>
      </select>
      <select
        v-model="sortDirection"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >
        <option value="asc">Asc</option>
        <option value="desc">Desc</option>
      </select>
    </div>
    <table class="w-full">
      <thead>
        <tr>
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Phone</th>
          <th class="px-4 py-2">AI Score</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lead in leads" :key="lead.id">
          <td class="border px-4 py-2">{{ lead.name }}</td>
          <td class="border px-4 py-2">{{ lead.email }}</td>
          <td class="border px-4 py-2">{{ lead.phone }}</td>
          <td class="border px-4 py-2">
            <span
              :class="[
                lead.ai_score >= 80 ? 'bg-green-500' : lead.ai_score >= 50 ? 'bg-yellow-500' : 'bg-red-500',
                'px-2 py-1 rounded text-white',
              ]"
            >
              {{ lead.ai_score }}%
            </span>
          </td>
          <td class="border px-4 py-2">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              @click="openEditModal(lead)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              @click="deleteLead(lead.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <Pagination :links="links" />
  </div>
</template>

<script>
import { InertiaHead } from '@inertiajs/inertia';
import Pagination from '@/Components/Pagination';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted } from 'vue';

export default {
  components: { InertiaHead, Pagination },
  props: {
    leads: Array,
    links: Object,
  },
  setup() {
    const searchQuery = ref('');
    const sortField = ref('name');
    const sortDirection = ref('asc');

    const openCreateModal = () => {
      Inertia.post('/leads/create');
    };

    const openEditModal = (lead) => {
      Inertia.post(`/leads/${lead.id}/edit`);
    };

    const deleteLead = (id) => {
      Inertia.delete(`/leads/${id}`);
    };

    const searchLeads = () => {
      Inertia.get('/leads', { search: searchQuery.value });
    };

    onMounted(() => {
      searchLeads();
    });

    return {
      searchQuery,
      sortField,
      sortDirection,
      openCreateModal,
      openEditModal,
      deleteLead,
      searchLeads,
    };
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
}

th,
td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f0f0f0;
}
</style>