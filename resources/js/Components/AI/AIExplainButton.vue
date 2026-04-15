// resources/js/Components/AI/AIExplainButton.vue

<template>
  <button
    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    @click="getAIExplanation"
  >
    Get AI Explanation
  </button>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { Ziggy } from 'ziggy-js';

export default {
  name: 'AIExplainButton',
  props: {
    score: {
      type: Number,
      required: true,
    },
  },
  methods: {
    getAIExplanation() {
      const url = Ziggy.route('ai.explain', { score: this.score });
      Inertia.visit(url);
    },
  },
};
</script>

<style scoped>
</style>