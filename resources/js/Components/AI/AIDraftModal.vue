// resources/js/Components/AI/AIDraftModal.vue

<template>
  <InertiaDialog
    :open="open"
    @close="close"
    :title="$t('ai.draft_email')"
    :max-width="1000"
    :max-height="600"
  >
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-bold">{{ $t('ai.draft_email') }}</h2>
        <button @click="close" class="text-gray-600 hover:text-gray-900">
          <XIcon class="w-4 h-4" />
        </button>
      </div>
    </template>

    <template #default>
      <div class="p-4">
        <div class="flex flex-col">
          <label for="tone" class="block text-sm font-medium text-gray-700">
            {{ $t('ai.tone') }}
          </label>
          <select
            id="tone"
            v-model="tone"
            class="mt-1 block w-full pl-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          >
            <option value="friendly">{{ $t('ai.friendly') }}</option>
            <option value="professional">{{ $t('ai.professional') }}</option>
            <option value="formal">{{ $t('ai.formal') }}</option>
          </select>
        </div>

        <div class="flex flex-col mt-4">
          <label for="length" class="block text-sm font-medium text-gray-700">
            {{ $t('ai.length') }}
          </label>
          <select
            id="length"
            v-model="length"
            class="mt-1 block w-full pl-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          >
            <option value="short">{{ $t('ai.short') }}</option>
            <option value="medium">{{ $t('ai.medium') }}</option>
            <option value="long">{{ $t('ai.long') }}</option>
          </select>
        </div>

        <div class="flex flex-col mt-4">
          <label for="subject" class="block text-sm font-medium text-gray-700">
            {{ $t('ai.subject') }}
          </label>
          <input
            id="subject"
            v-model="subject"
            type="text"
            class="mt-1 block w-full pl-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>

        <div class="flex flex-col mt-4">
          <label for="body" class="block text-sm font-medium text-gray-700">
            {{ $t('ai.body') }}
          </label>
          <textarea
            id="body"
            v-model="body"
            class="mt-1 block w-full pl-3 py-2 text-base border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
        </div>

        <button
          @click="draftEmail"
          class="mt-4 w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          {{ $t('ai.draft_email') }}
        </button>
      </div>
    </template>
  </InertiaDialog>
</template>

<script>
import { defineComponent, ref } from 'vue';
import { InertiaDialog } from '@inertiajs/inertia-vue3';
import { Ziggy } from 'ziggy-js';
import axios from 'axios';

export default defineComponent({
  components: { InertiaDialog },
  props: {
    open: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      tone: 'friendly',
      length: 'short',
      subject: '',
      body: '',
    };
  },
  methods: {
    close() {
      this.$emit('close');
    },
    async draftEmail() {
      try {
        const response = await axios.post(
          route('ai.draft-email'),
          {
            tone: this.tone,
            length: this.length,
            subject: this.subject,
            body: this.body,
          },
          {
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

        this.$emit('email-drafted', response.data);
      } catch (error) {
        console.error(error);
      }
    },
  },
});
</script>

<style scoped>
/* Add your styles here */
</style>