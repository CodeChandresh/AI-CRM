// No PHP code is required for this file, as it's a Vue.js component.
// The following is the Vue.js component code for the ChurnRiskMeter.vue file.

<template>
  <div class="flex flex-col items-center justify-center h-screen">
    <div class="bg-white rounded-lg shadow-md p-4 w-64">
      <h2 class="text-lg font-bold mb-4">Churn Risk Meter</h2>
      <div class="flex items-center justify-between w-full">
        <div class="bg-red-500 h-4 w-4 rounded-full"></div>
        <div class="text-lg font-bold">{{ riskPercentage }}%</div>
        <div class="bg-green-500 h-4 w-4 rounded-full"></div>
      </div>
      <div class="mt-4">
        <p class="text-sm text-gray-600">Based on AI analysis, this customer has a {{ riskPercentage }}% chance of churning.</p>
        <p class="text-sm text-gray-600">Explanation: {{ explanation }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    riskPercentage: {
      type: Number,
      required: true
    },
    explanation: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
  .bg-red-500 {
    background-color: #ff3737;
  }

  .bg-green-500 {
    background-color: #34c759;
  }
</style>