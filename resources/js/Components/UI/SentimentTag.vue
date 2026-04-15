// No PHP code is required for this Vue.js component.
// The following is the Vue.js component code in resources/js/Components/UI/SentimentTag.vue

<template>
  <div class="flex items-center">
    <i :class="iconClass" class="mr-2"></i>
    <span :class="colorClass">{{ sentiment }}</span>
  </div>
</template>

<script>
export default {
  props: {
    sentiment: {
      type: String,
      required: true,
    },
  },
  computed: {
    iconClass() {
      if (this.sentiment === 'Positive') {
        return 'heroicon-o-smile';
      } else if (this.sentiment === 'Negative') {
        return 'heroicon-o-frown';
      } else {
        return 'heroicon-o-neutral-face';
      }
    },
    colorClass() {
      if (this.sentiment === 'Positive') {
        return 'text-green-500';
      } else if (this.sentiment === 'Negative') {
        return 'text-red-500';
      } else {
        return 'text-gray-500';
      }
    },
  },
};
</script>

<style scoped>
/* Add custom styles for the sentiment tag here */
</style>