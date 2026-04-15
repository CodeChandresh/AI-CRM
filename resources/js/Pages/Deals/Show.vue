// resources/js/Pages/Deals/Show.vue

<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Deal Details</h1>
    <div class="flex flex-wrap -mx-4">
      <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4">
        <div class="bg-white rounded shadow-md p-4">
          <h2 class="text-lg font-bold mb-2">Probability</h2>
          <p class="text-gray-600 text-sm">{{ deal.probability }}%</p>
        </div>
      </div>
      <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4">
        <div class="bg-white rounded shadow-md p-4">
          <h2 class="text-lg font-bold mb-2">Value</h2>
          <p class="text-gray-600 text-sm">${{ deal.value }}</p>
        </div>
      </div>
      <div class="w-full md:w-1/2 xl:w-1/3 px-4 mb-4">
        <div class="bg-white rounded shadow-md p-4">
          <h2 class="text-lg font-bold mb-2">Timeline</h2>
          <p class="text-gray-600 text-sm">{{ deal.timeline }}</p>
        </div>
      </div>
    </div>
    <h2 class="text-lg font-bold mb-2 mt-4">Related Contacts</h2>
    <div class="bg-white rounded shadow-md p-4">
      <ul>
        <li v-for="contact in deal.contacts" :key="contact.id">
          {{ contact.name }} ({{ contact.email }})
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/inertia';
import { Ziggy } from 'ziggy-js';

export default {
  props: {
    deal: Object,
  },
  data() {
    return {
      contacts: [],
    };
  },
  mounted() {
    this.getContacts();
  },
  methods: {
    getContacts() {
      axios.get('/api/deal/contacts/' + this.deal.id)
        .then(response => {
          this.contacts = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>