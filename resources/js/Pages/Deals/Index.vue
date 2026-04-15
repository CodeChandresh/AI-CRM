// resources/js/Pages/Deals/Index.vue

<template>
  <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
    <InertiaHead title="Deals" />
    <h1 class="text-3xl font-bold mb-4">Deals</h1>
    <div class="flex flex-col">
      <div class="overflow-x-auto">
        <table class="table-auto w-full">
          <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
            <tr>
              <th class="p-2">Deal Name</th>
              <th class="p-2">Stage</th>
              <th class="p-2">Amount</th>
              <th class="p-2">Probability</th>
              <th class="p-2">Actions</th>
            </tr>
          </thead>
          <tbody class="text-sm font-medium text-gray-500">
            <tr v-for="deal in deals" :key="deal.id">
              <td class="p-2">{{ deal.name }}</td>
              <td class="p-2">{{ deal.stage }}</td>
              <td class="p-2">${{ deal.amount }}</td>
              <td class="p-2">{{ deal.probability }}%</td>
              <td class="p-2">
                <button
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  @click="editDeal(deal)"
                >
                  Edit
                </button>
                <button
                  class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                  @click="deleteDeal(deal)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex justify-end mt-4">
        <button
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          @click="createDeal"
        >
          Create Deal
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/inertia';
import { InertiaHead } from '@inertiajs/inertia';
import { defineComponent, ref } from 'vue';
import { Ziggy } from 'ziggy-js';

export default defineComponent({
  components: { InertiaHead },
  props: {
    deals: Array,
  },
  setup() {
    const createDeal = () => {
      window.location.href = Ziggy.route('deals.create');
    };

    const editDeal = (deal) => {
      window.location.href = Ziggy.route('deals.edit', { id: deal.id });
    };

    const deleteDeal = (deal) => {
      if (confirm('Are you sure you want to delete this deal?')) {
        axios.delete(Ziggy.route('deals.destroy', { id: deal.id })).then(() => {
          window.location.reload();
        });
      }
    };

    return {
      createDeal,
      editDeal,
      deleteDeal,
    };
  },
});
</script>