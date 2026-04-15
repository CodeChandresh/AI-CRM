// No PHP code is required for this file, as it's a Vue.js component.
// The following is the Vue.js component code for the AIScoreBadge.vue file.

<template>
  <div class="flex items-center justify-center w-12 h-12 rounded-full">
    <div
      class="w-12 h-12 rounded-full flex items-center justify-center"
      :class="getBadgeClass(score)"
    >
      <span class="text-lg font-bold">{{ score }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    score: {
      type: Number,
      required: true,
    },
  },
  methods: {
    getBadgeClass(score) {
      if (score < 0) {
        return 'bg-red-500';
      } else if (score < 50) {
        return 'bg-yellow-500';
      } else {
        return 'bg-green-500';
      }
    },
  },
};
</script>

<style scoped>
.bg-red-500 {
  background-color: #ff3737;
}

.bg-yellow-500 {
  background-color: #f7dc6f;
}

.bg-green-500 {
  background-color: #34c759;
}
</style>