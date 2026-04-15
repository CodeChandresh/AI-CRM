// resources/js/Pages/Leads/Create.vue

<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Create Lead</h1>
    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
        <div v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</div>
      </div>
      <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
        <div v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email[0] }}</div>
      </div>
      <div class="mb-4">
        <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
        <input
          id="phone"
          v-model="form.phone"
          type="tel"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          required
        />
        <div v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone[0] }}</div>
      </div>
      <button
        type="submit"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
      >
        Create Lead
      </button>
    </form>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { Head } from '@inertiajs/inertia';
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
      },
      errors: {},
    };
  },
  methods: {
    submitForm() {
      axios
        .post('/leads', this.form)
        .then((response) => {
          console.log(response.data);
          this.form = {
            name: '',
            email: '',
            phone: '',
          };
          this.errors = {};
        })
        .catch((error) => {
          this.errors = error.response.data.errors;
        });
    },
  },
};
</script>