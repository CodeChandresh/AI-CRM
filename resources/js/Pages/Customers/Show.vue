// resources/js/Pages/Customers/Show.vue

<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Customer 360 View</h1>

    <div class="flex flex-col md:flex-row mb-4">
      <div class="w-full md:w-1/2">
        <h2 class="text-lg font-bold mb-2">Customer Information</h2>
        <p>
          <span class="font-bold">Name:</span> {{ customer.name }}
        </p>
        <p>
          <span class="font-bold">Email:</span> {{ customer.email }}
        </p>
        <p>
          <span class="font-bold">Phone:</span> {{ customer.phone }}
        </p>
      </div>

      <div class="w-full md:w-1/2">
        <h2 class="text-lg font-bold mb-2">AI Predictions</h2>
        <p>
          <span class="font-bold">Lead Score:</span> {{ customer.lead_score }}
        </p>
        <p>
          <span class="font-bold">Churn Prediction:</span> {{ customer.churn_prediction }}
        </p>
      </div>
    </div>

    <h2 class="text-lg font-bold mb-2">Activity Timeline</h2>
    <ul>
      <li v-for="activity in customer.activities" :key="activity.id">
        {{ activity.description }} ({{ activity.created_at }})
      </li>
    </ul>

    <h2 class="text-lg font-bold mb-2">Deals</h2>
    <ul>
      <li v-for="deal in customer.deals" :key="deal.id">
        {{ deal.name }} ({{ deal.amount }})
      </li>
    </ul>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import { Ziggy } from 'ziggy-js';

export default {
  props: {
    customer: Object,
  },

  setup() {
    const page = usePage();

    const getCustomer = async () => {
      try {
        const response = await Inertia.get(route('customers.show', page.props.customer.id));
        return response.data.customer;
      } catch (error) {
        console.error(error);
      }
    };

    return {
      getCustomer,
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>