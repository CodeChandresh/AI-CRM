// No PHP code is required for this Vue.js component
// resources/js/Components/UI/StatCard.vue

<template>
  <div class="flex flex-col items-center justify-center h-full">
    <div class="flex items-center justify-center mb-4">
      <svg-icon :name="icon" :class="iconClass" />
    </div>
    <div class="text-3xl font-bold mb-2">
      {{ value }}
    </div>
    <div class="text-gray-600 text-sm">
      {{ label }}
    </div>
    <div class="flex items-center justify-center mt-4">
      <div class="bg-green-100 text-green-800 py-1 px-2 rounded-full">
        {{ trend }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      required: true,
    },
    iconClass: {
      type: String,
      default: '',
    },
    value: {
      type: [Number, String],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    trend: {
      type: String,
      default: '',
    },
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>