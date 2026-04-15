// resources/js/Pages/Dashboard.vue

<template>
  <div class="max-w-7xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-2">Total Leads</h2>
        <p class="text-3xl font-bold mb-4">{{ totalLeads }}</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-2">New Leads</h2>
        <p class="text-3xl font-bold mb-4">{{ newLeads }}</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-2">Conversion Rate</h2>
        <p class="text-3xl font-bold mb-4">{{ conversionRate }}%</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-2">Average Deal Value</h2>
        <p class="text-3xl font-bold mb-4">{{ averageDealValue }}</p>
      </div>
    </div>

    <div class="mt-4">
      <h2 class="text-2xl font-bold mb-2">Recent Activities</h2>
      <ul>
        <li v-for="activity in recentActivities" :key="activity.id" class="mb-2">
          {{ activity.name }} {{ activity.description }}
        </li>
      </ul>
    </div>

    <div class="mt-4">
      <h2 class="text-2xl font-bold mb-2">AI Insights</h2>
      <div class="bg-white rounded-lg shadow-md p-4">
        <h3 class="text-lg font-bold mb-2">Lead Scoring</h3>
        <p class="text-lg font-bold mb-4">{{ leadScoring }}</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-4 mt-4">
        <h3 class="text-lg font-bold mb-2">Sentiment Analysis</h3>
        <p class="text-lg font-bold mb-4">{{ sentimentAnalysis }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import Ziggy from 'ziggy-js';

export default {
  setup() {
    const { props } = usePage();

    const totalLeads = props.totalLeads;
    const newLeads = props.newLeads;
    const conversionRate = props.conversionRate;
    const averageDealValue = props.averageDealValue;
    const recentActivities = props.recentActivities;
    const leadScoring = props.leadScoring;
    const sentimentAnalysis = props.sentimentAnalysis;

    return {
      totalLeads,
      newLeads,
      conversionRate,
      averageDealValue,
      recentActivities,
      leadScoring,
      sentimentAnalysis,
    };
  },
};
</script>

<style scoped>
.grid {
  @apply grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4;
}

.card {
  @apply bg-white rounded-lg shadow-md p-4;
}
</style>