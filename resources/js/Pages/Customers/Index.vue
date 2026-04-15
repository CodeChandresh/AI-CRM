// resources/js/Pages/Customers/Index.vue

<template>
  <div>
    <InertiaHead title="Customers" />
    <h1 class="text-3xl font-bold mb-4">Customers</h1>
    <div class="flex justify-between mb-4">
      <button
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="$inertia.visit('/customers/create')"
      >
        Create Customer
      </button>
      <button
        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
        @click="$inertia.visit('/customers/export')"
      >
        Export Customers
      </button>
    </div>
    <table class="w-full">
      <thead>
        <tr>
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Churn Risk</th>
          <th class="px-4 py-2">LTV</th>
          <th class="px-4 py-2">Health Score</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="customer in customers" :key="customer.id">
          <td class="px-4 py-2">{{ customer.name }}</td>
          <td class="px-4 py-2">{{ customer.email }}</td>
          <td class="px-4 py-2">
            <div
              :class="[
                customer.churn_risk >= 50 ? 'bg-red-500' : 'bg-green-500',
                'text-white font-bold py-2 px-4 rounded',
              ]"
            >
              {{ customer.churn_risk }}%
            </div>
          </td>
          <td class="px-4 py-2">${{ customer.ltv }}</td>
          <td class="px-4 py-2">
            <div
              :class="[
                customer.health_score >= 50 ? 'bg-red-500' : 'bg-green-500',
                'text-white font-bold py-2 px-4 rounded',
              ]"
            >
              {{ customer.health_score }}%
            </div>
          </td>
          <td class="px-4 py-2">
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              @click="$inertia.visit(`/customers/${customer.id}/edit`)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
              @click="$inertia.delete(`/customers/${customer.id}`)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia';
import { InertiaHead } from '@inertiajs/inertia';

export default {
  components: { InertiaHead },
  props: {
    customers: Array,
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
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