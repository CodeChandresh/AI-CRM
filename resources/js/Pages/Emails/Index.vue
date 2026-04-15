// resources/js/Pages/Emails/Index.vue

<template>
  <div>
    <h1 class="text-3xl font-bold mb-4">Email Campaigns</h1>
    <InertiaLink :href="route('emails.create')" class="btn btn-primary mb-4">Create New Email Campaign</InertiaLink>
    <table class="w-full table-auto">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-4 py-2">Campaign Name</th>
          <th class="px-4 py-2">AI Draft Status</th>
          <th class="px-4 py-2">Open Rate</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="email in emails" :key="email.id" class="border-b">
          <td class="px-4 py-2">{{ email.name }}</td>
          <td class="px-4 py-2">
            <span v-if="email.ai_draft_status === 'draft'">Draft</span>
            <span v-else-if="email.ai_draft_status === 'sent'">Sent</span>
            <span v-else-if="email.ai_draft_status === 'failed'">Failed</span>
            <span v-else-if="email.ai_draft_status === 'success'">Success</span>
          </td>
          <td class="px-4 py-2">{{ email.open_rate }}%</td>
          <td class="px-4 py-2">
            <InertiaLink :href="route('emails.show', email.id)" class="btn btn-sm btn-primary mr-2">View</InertiaLink>
            <InertiaLink :href="route('emails.edit', email.id)" class="btn btn-sm btn-secondary mr-2">Edit</InertiaLink>
            <button @click="deleteEmail(email.id)" class="btn btn-sm btn-danger">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { InertiaLink } from '@inertiajs/inertia-vue3';
import { Ziggy } from 'ziggy-js';

export default {
  components: { InertiaLink },
  props: {
    emails: Array,
  },
  methods: {
    deleteEmail(id) {
      if (confirm('Are you sure you want to delete this email campaign?')) {
        this.$inertia.delete(route('emails.destroy', id));
      }
    },
  },
};
</script>

<style scoped>
table {
  @apply w-full;
}
th, td {
  @apply px-4 py-2;
}
th {
  @apply bg-gray-100;
}
</style>