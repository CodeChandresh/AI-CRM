// resources/js/Pages/Leads/Show.vue

<template>
  <div>
    <h1>Lead Details</h1>
    <div class="flex flex-col">
      <div class="flex flex-row">
        <div class="w-1/2">
          <h2>Lead Information</h2>
          <p>
            <strong>Name:</strong>
            {{ lead.name }}
          </p>
          <p>
            <strong>Email:</strong>
            {{ lead.email }}
          </p>
          <p>
            <strong>Phone:</strong>
            {{ lead.phone }}
          </p>
        </div>
        <div class="w-1/2">
          <h2>AI Score Breakdown</h2>
          <p>
            <strong>Lead Score:</strong>
            {{ lead.score }}
          </p>
          <p>
            <strong>Lead Status:</strong>
            {{ lead.status }}
          </p>
          <p>
            <strong>Lead Source:</strong>
            {{ lead.source }}
          </p>
        </div>
      </div>
      <div class="flex flex-row">
        <div class="w-1/2">
          <h2>Email History</h2>
          <ul>
            <li v-for="email in lead.emails" :key="email.id">
              {{ email.subject }} - {{ email.sent_at }}
            </li>
          </ul>
        </div>
        <div class="w-1/2">
          <h2>Timeline</h2>
          <ul>
            <li v-for="event in lead.events" :key="event.id">
              {{ event.description }} - {{ event.created_at }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import { Ziggy } from 'ziggy-js';

export default {
  props: {
    lead: Object,
  },
  setup() {
    const page = usePage();
    const ziggy = new Ziggy(page.props.url);

    return {
      ziggy,
    };
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>